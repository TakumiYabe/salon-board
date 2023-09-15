import React, {Fragment, useState, useEffect} from 'react';
import {createRoot} from "react-dom/client";
import axios from 'axios';
import classNames from 'classnames';

import SelectYearMonth from "../common/SelectYearMonth.jsx";
import {formatDayWeek} from "../common/common.js";

function Edit() {
    const element = $('#edit-shift-submissions');
    const staffId = element.data('staff-id');
    const yearMonthList = element.data('year-month-list');
    const shiftConfig = element.data('config-shift');
    const selectedYearAndMonth = yearMonthList[0];
    const [shiftTypes, setShiftTypes] = useState([]);
    const [shiftSubmission, setShiftSubmission] = useState([]);
    const [loading, setLoading] = useState(true);

    // シフトタイプマップ
    const shiftTypeMaps = {};
    shiftTypes.forEach(function (shiftType) {
        shiftTypeMaps[shiftType['id']] = shiftType['work_time_from'] + '-' + shiftType['work_time_to']
    });
    shiftTypeMaps['x'] = '';

    useEffect(() => {
        get(selectedYearAndMonth);
    }, [selectedYearAndMonth]);

    const editChange = (event, index=null, field) => {
        var updatedShiftSubmission =  { ...shiftSubmission};
        var updatedShiftSubmissionDetails = [...shiftSubmission.shift_submission_details];

        if (index === null) {
            updatedShiftSubmission['memo'] = event.target.value;
        } else if (field === 'check') {
            if (event.target.value === 'x') {
                updatedShiftSubmissionDetails[index]['is_work_off'] = 1;
                updatedShiftSubmissionDetails[index]['shift_type_id'] = null;
                updatedShiftSubmissionDetails[index]['work_time_from'] = null;
                updatedShiftSubmissionDetails[index]['work_time_to'] = null;
            } else {
                updatedShiftSubmissionDetails[index]['is_work_off'] = 0;
                updatedShiftSubmissionDetails[index]['shift_type_id'] = parseInt(event.target.value);
                updatedShiftSubmissionDetails[index]['work_time_from'] = shiftTypeMaps[event.target.value].split('-')[0];
                updatedShiftSubmissionDetails[index]['work_time_to'] = shiftTypeMaps[event.target.value].split('-')[1];
            }
        } else {
            updatedShiftSubmissionDetails[index][field] = event.target.value;
        }
        updatedShiftSubmission['shift_submission_details'] = updatedShiftSubmissionDetails
        setShiftSubmission(updatedShiftSubmission);
    };

    const get = async (value) => {
        await axios
            .post('/api/shiftSubmissions/get', {
                staff_id: staffId,
                year_and_month: value,
            })
            .then(response => {
                setShiftSubmission(response.data.shift_submission);
                setShiftTypes(response.data.shift_types);
                setLoading(false);
            }).catch(() => {
                console.log('通信に失敗しました');
                setLoading(false);
            });
    }

    function DisplayShiftSubmissions (props) {
        const shiftSubmission = props.shiftSubmission;
        return (
            <div className="table-sticky">
                <table className="shift-table vertical-table table-sticky-container">
                    <thead>
                    <tr>
                        <th className="table-sticky-title">日(曜日)</th>
                        {shiftTypes.map((shiftType, index) => {
                            return <th className="table-sticky-title"
                                key={index}>{shiftType['name']}({shiftType['work_time_from']}-{shiftType['work_time_to']})</th>
                        })}
                        <th className="table-sticky-title">開始時間</th>
                        <th className="table-sticky-title">終了時間</th>
                        <th className="table-sticky-title">休み</th>
                    </tr>
                    </thead>
                    <tbody className="js-shift-rows">
                    {shiftSubmission.shift_submission_details.map((shiftSubmissionDetail, shiftSubmissionDetailIndex) => {
                        if (shiftSubmissionDetail.work_time_from || shiftSubmissionDetail.work_time_to) {
                            shiftSubmissionDetail.is_work_off = 0;
                        }

                        var classes = '';
                        if (shiftSubmissionDetail.is_work_off) {
                            classes = "js-shift-row work-off-row";
                        } else if (shiftSubmissionDetail.work_time_from && shiftSubmissionDetail.work_time_to) {
                            classes = "js-shift-row entered-row";
                        } else {
                            classes = "js-shift-row";
                        }
                        return (
                            <tr key={shiftSubmissionDetailIndex}
                                className={classes}
                                data-row-number={shiftSubmissionDetailIndex}>
                                <input type="hidden"
                                       name={shiftSubmissionDetailIndex + '.id'}
                                       value={shiftSubmissionDetail.id}/>
                                <input type="hidden"
                                       name={shiftSubmissionDetailIndex + '.date'}
                                       value={shiftSubmissionDetailIndex + 1}/>
                                <td>{shiftSubmissionDetail['formatted_date']}</td>
                                {shiftTypes.map((shiftType, shiftTypeIndex) => {
                                    return <td key={shiftTypeIndex}>
                                        <input type="radio"
                                               name={shiftSubmissionDetailIndex + '.check'}
                                               value={shiftType['id']}
                                               checked={shiftType['id'] === shiftSubmissionDetail['shift_type_id']}
                                               onChange={(e) => editChange(e, shiftSubmissionDetailIndex, 'check')}
                                        />
                                    </td>
                                })}
                                <td>
                                    <input type="time"
                                           className="table-input"
                                           name={shiftSubmissionDetailIndex + '.work_time_from'}
                                           value={shiftSubmissionDetail.work_time_from || ""}
                                           min={shiftConfig['start_time']}
                                           max={shiftConfig['end_time']}
                                           onChange={(e) => editChange(e, shiftSubmissionDetailIndex, 'work_time_from')}/>
                                </td>
                                <td>
                                    <input type="time"
                                           className="table-input"
                                           name={shiftSubmissionDetailIndex + '.work_time_to'}
                                           value={shiftSubmissionDetail.work_time_to || ""}
                                           min={shiftConfig['start_time']}
                                           max={shiftConfig['end_time']}
                                           onChange={(e) => editChange(e, shiftSubmissionDetailIndex, 'work_time_to')}/>
                                </td>
                                <td>
                                    <input type="radio"
                                           className="js-work-off"
                                           name={shiftSubmissionDetailIndex + '.check'}
                                           value='x'
                                           checked={shiftSubmissionDetail.is_work_off}
                                           onChange={(e) => editChange(e, shiftSubmissionDetailIndex, 'check')}
                                    />
                                </td>
                            </tr>
                        )
                    })}
                    </tbody>
                </table>
            </div>
        );
    }

    function CountRowsTable (props) {
        const shiftSubmission = props.shiftSubmission;
        var notEnteredRowCount = shiftSubmission.shift_submission_details.length;
        var enteredRowCount = 0;
        var workOffRowCount = 0;

        shiftSubmission.shift_submission_details.forEach(function (shiftSubmissionDetail) {
            if (shiftSubmissionDetail.is_work_off) {
                workOffRowCount ++;
                notEnteredRowCount--;
            } else if (shiftSubmissionDetail.work_time_from && shiftSubmissionDetail.work_time_to) {
                enteredRowCount ++;
                notEnteredRowCount--;
            }
        });

        return (
            <table className="count-rows-table">
                <thead>
                <tr>
                    <th>未入力</th>
                    <th>出勤希望日数</th>
                    <th>休み希望日数</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{notEnteredRowCount}</td>
                    <td>{enteredRowCount}</td>
                    <td>{workOffRowCount}</td>
                </tr>
                </tbody>
            </table>
        );
    }

    if (loading) {
        return <div>Loading...</div>; // ローディング中の表示
    } else {
        return (
            <Fragment>
                <SelectYearMonth
                    yearMonthList={yearMonthList}
                    function={get}
                />
                <div className="table-form-whole">
                    <form method="POST" action={`/shift-submissions/edit/${staffId}`}>
                        <div className="display-flex">
                            <CountRowsTable
                                shiftSubmission={shiftSubmission}
                            />
                            <div className="display-column second-index">
                                <label>共有事項</label>
                                <textarea
                                    name="memo"
                                    className="input-memo"
                                    placeholder="シフトに関して共有事項があれば記入してください。"
                                    value={shiftSubmission['memo']}
                                    onChange={(e) => editChange(e, null, 'memo')}
                                />
                            </div>
                        </div>
                        <div className="form-buttons">
                            <input className="form-submit" type="submit" value="登録" />
                        </div>
                        <div className="form-start">
                            <input name="id"
                                   type="hidden"
                                   id="id"
                                   value={shiftSubmission['id']} />
                            <input type="hidden" name="_token" value={csrfToken} />
                            <input name="year_and_month"
                                   type="hidden"
                                   id="year_and_month"
                                   value={selectedYearAndMonth} />

                        </div>
                        <DisplayShiftSubmissions
                            shiftSubmission={shiftSubmission}
                        />
                    </form>
                </div>
            </Fragment>
        );
    }
}

export default Edit;

if (document.getElementById('edit-shift-submissions')) {
    createRoot(document.getElementById('edit-shift-submissions')).render(<Edit/>);
}
