import React, {Fragment, useEffect, useState} from "react";
import {createRoot} from "react-dom/client";
import axios from "axios";
import SelectYearMonth from "../common/SelectYearMonth.jsx";

function Edit() {
    const element = $('#edit-shifts');
    const yearMonthList = element.data('year-month-list');
    const selectedYearAndMonth = yearMonthList[0];
    const [staffs, setStaffs] = useState([]);
    const [shiftTypes, setShiftTypes] = useState([]);
    const [shiftSubmissions, setShiftSubmissions] = useState([]);
    const [shift, setShift] = useState([]);
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

    const editChange = (event, key, index, field) => {
        if (event.target.readOnly) {
            return;
        }

        var updatedShift = {...shift};
        var updatedShiftDetails = {...shift.details};

        if (field === 'check') {
            if (event.target.value === 'x') {
                updatedShiftDetails[key][index]['is_work_off'] = 1;
                updatedShiftDetails[key][index]['shift_type_id'] = null;
                updatedShiftDetails[key][index]['work_time_from'] = null;
                updatedShiftDetails[key][index]['work_time_to'] = null;
            } else {
                updatedShiftDetails[key][index]['is_work_off'] = 0;
                updatedShiftDetails[key][index]['shift_type_id'] = parseInt(event.target.value);
                updatedShiftDetails[key][index]['work_time_from'] = shiftTypeMaps[event.target.value].split('-')[0];
                updatedShiftDetails[key][index]['work_time_to'] = shiftTypeMaps[event.target.value].split('-')[1];
            }
        } else {
            updatedShiftDetails[key][index][field] = event.target.value;
        }
        updatedShift['shift_details'] = updatedShiftDetails;
        setShift(updatedShift);
    };

    const get = async (value) => {
        await axios
            .post('/api/shifts/get', {
                year_and_month: value,
            })
            .then(response => {
                setShift(response.data.shift);
                setShiftSubmissions(response.data.shift_submissions);
                setStaffs(response.data.staffs);
                setShiftTypes(response.data.shift_types);
                setLoading(false);
            }).catch(() => {
                console.log('通信に失敗しました');
                setLoading(false);
            });
    }

    function DisplayShifts(props) {
        const shift = props.shift;
        const shiftSubmissions = props.shiftSubmissions;

        return (
            <div className="table-sticky">
                <table className="shift-table vertical-table table-sticky-container">
                    <thead className="sticky-header">
                    <tr>
                        <th className="table-sticky-title">日(曜日)</th>
                        <th className="table-sticky-title">社員名</th>
                        <th className="table-sticky-title">出勤希望</th>
                        {shiftTypes.map((shiftType, shiftTypeIndex) => {
                            return <th className="table-sticky-title"
                                key={shiftTypeIndex}>{shiftType['name']}({shiftType['work_time_from']}-{shiftType['work_time_to']})</th>
                        })}
                        <th className="table-sticky-title">開始時間</th>
                        <th className="table-sticky-title">終了時間</th>
                        <th className="table-sticky-title">休み</th>
                        <th className="table-sticky-title">合計出勤人数</th>
                        <th className="table-sticky-title">想定合計出勤時間</th>
                        <th className="table-sticky-title">想定合計休憩時間</th>
                        <th className="table-sticky-title">想定人件費</th>
                    </tr>
                    </thead>
                    <tbody className="js-shift-rows">
                    {Object.keys(shift.details).map((key, index) => {
                        return (
                            <React.Fragment key={index}>
                                {shift.details[key].map((shift_detail, shiftIndex) => {
                                    const date = shift_detail.date;
                                    const staffId = parseInt(shift_detail.staff_id);
                                    const shiftSubmission = shiftSubmissions[staffId] ? shiftSubmissions[staffId].details[date] : null;
                                    var shiftSubmissionRequest = '';
                                    var classes = "";
                                    var formReadOnly = false;
                                    var isWorkOff = false;
                                    var minTime = '';
                                    var maxTime = '';
                                    var rowSpan = shift.details[key].length;

                                    if (!shiftSubmission) {
                                        formReadOnly = true;
                                        isWorkOff = true;
                                        shiftSubmissionRequest = 'シフト未提出';
                                        classes = "not-entered-row disabled-row";
                                    } else {
                                        minTime = shiftSubmission.work_time_from;
                                        maxTime = shiftSubmission.work_time_to;

                                        if (shiftSubmission.is_work_off) {
                                            shiftSubmissionRequest = '休み';
                                            isWorkOff = true;
                                            classes = "work-off-row disabled-row";
                                        } else {
                                            shiftSubmissionRequest = shiftSubmission.work_time_from + '-' + shiftSubmission.work_time_to;
                                            isWorkOff = shift_detail.is_work_off;
                                            if (isWorkOff) {
                                                classes = "work-off-row";
                                            } else if (shift_detail.work_time_from && shift_detail.work_time_to) {
                                                classes = "entered-row";
                                            }
                                        }
                                    }

                                    var shiftDetailsPerDay = shift.details[key];
                                    var personNumber = 0;
                                    var totalWorkTime = 0;
                                    var totalRestTime = 0;
                                    var totalCost = 0;

                                    shiftDetailsPerDay.forEach(function (shiftDetail) {
                                        if (shiftDetail['work_time_from'] && shiftDetail['work_time_to']) {
                                            // 出勤人数
                                            personNumber++;

                                            // 想定合計出勤時間・想定合計休憩時間
                                            var diffWorkTime = new Date(shiftDetail['date'] + 'T18:00:00') - new Date(shiftDetail['date'] + 'T' + shiftDetail['work_time_from']);
                                            var diffOverWorkTime = new Date(shiftDetail['date'] + 'T' + shiftDetail['work_time_to']) - new Date(shiftDetail['date'] + 'T18:00:00');
                                            var diffTime = diffWorkTime + diffOverWorkTime;
                                            var workTime = 0;
                                            var overWorkTime = diffOverWorkTime;
                                            var restTime = 0;
                                            if (31500000 <= diffTime) {
                                                restTime = 2700000;
                                                workTime = diffWorkTime - restTime;
                                                totalRestTime += restTime;
                                                totalWorkTime += (workTime + overWorkTime);
                                            } else if (23400000 <= diffTime) {
                                                restTime = 1800000;
                                                workTime = diffWorkTime - restTime;
                                                totalRestTime += restTime;
                                                totalWorkTime += (workTime + overWorkTime);
                                            } else {
                                                totalWorkTime += (workTime + overWorkTime);
                                            }

                                            // 想定人件費
                                            var staffId = shiftDetail['staff_id'];
                                            var secondSalary = staffs[staffId]['hourly_wage'] / 60 / 60;
                                            var overSecondSalary = secondSalary * 1.25;

                                            totalCost += (secondSalary * workTime / 1000 + overSecondSalary * overWorkTime / 1000);
                                        }
                                    });

                                    var totalWorkHourTime = Math.floor(totalWorkTime / (1000 * 60 * 60));
                                    var totalWorkMinuteTime = Math.floor((totalWorkTime - totalWorkHourTime * 1000 * 60 * 60) / (1000 * 60));
                                    var totalRestHourTime = Math.floor(totalRestTime / (1000 * 60 * 60));
                                    var totalRestMinuteTime = Math.floor((totalRestTime - totalRestHourTime * 1000 * 60 * 60) / (1000 * 60));

                                    return (
                                        <tr key={shiftIndex} className={classes}>
                                            {!shiftIndex ? (
                                                <td className="no-color-cell" rowSpan={rowSpan}>{key}</td>) : null}
                                            <input type="hidden"
                                                   name={shift_detail['date'] + '.' + staffId + '.id'}
                                                   value={shift_detail.id}/>
                                            <input type="hidden"
                                                   name={shift_detail['date'] + '.' + staffId + '.staff_id'}
                                                   value={shift_detail.staff_id}/>
                                            <input type="hidden"
                                                   name={shift_detail['date'] + '.' + staffId + '.date'}
                                                   value={date}/>
                                            <React.Fragment>
                                                <td>{staffs[staffId]['name']}</td>
                                                <td className="shift-submission-request">{shiftSubmissionRequest}</td>
                                                {shiftTypes.map((shiftType, shiftTypeIndex) => {
                                                    return (
                                                        <td key={shiftTypeIndex}>
                                                            <input type="radio"
                                                                   name={shift_detail['date'] + '.' + staffId + '.check'}
                                                                   value={shiftType['id']}
                                                                   readOnly={formReadOnly}
                                                                   checked={shiftType['id'] === shift_detail['shift_type_id']}
                                                                   onChange={(e) => editChange(e, key, shiftIndex, 'check')}
                                                            />
                                                        </td>
                                                    );
                                                })}
                                                <td>
                                                    <input type="time"
                                                           className="js-shift-work_time_from"
                                                           name={shift_detail['date'] + '.' + staffId + '.work_time_from'}
                                                           value={shift_detail.work_time_from || ""}
                                                           min={minTime}
                                                           max={maxTime}
                                                           readOnly={formReadOnly}
                                                           onChange={(e) => editChange(e, key, shiftIndex, 'work_time_from')}
                                                    />
                                                </td>
                                                <td>
                                                    <input type="time"
                                                           className="js-shift-work_time_to"
                                                           name={shift_detail['date'] + '.' + staffId + '.work_time_to'}
                                                           value={shift_detail.work_time_to || ""}
                                                           min={minTime}
                                                           max={maxTime}
                                                           readOnly={formReadOnly}
                                                           onChange={(e) => editChange(e, key, shiftIndex, 'work_time_to')}
                                                    />
                                                </td>
                                                <td>
                                                    <input type="radio"
                                                           className="js-work-off"
                                                           name={shift_detail['date'] + '.' + staffId + '.check'}
                                                           value='x'
                                                           checked={isWorkOff}
                                                           readOnly={formReadOnly}
                                                           onChange={(e) => editChange(e, key, shiftIndex, 'check')}
                                                    />
                                                </td>
                                                {!shiftIndex ? (
                                                    <td className="no-color-cell" rowSpan={rowSpan}>{personNumber}</td>
                                                ) : null}
                                                {!shiftIndex ? (
                                                    <td className="no-color-cell"
                                                        rowSpan={rowSpan}>{totalWorkHourTime + ':' + totalWorkMinuteTime}</td>
                                                ) : null}
                                                {!shiftIndex ? (
                                                    <td className="no-color-cell"
                                                        rowSpan={rowSpan}>{totalRestHourTime + ':' + totalRestMinuteTime}</td>
                                                ) : null}
                                                {!shiftIndex ? (
                                                    <td className="no-color-cell" rowSpan={rowSpan}>{totalCost}</td>
                                                ) : null}
                                            </React.Fragment>
                                        </tr>
                                    );
                                })}
                            </React.Fragment>
                        );
                    })}
                    </tbody>
                </table>
            </div>
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
                <div className="contents">
                    <label>共有事項</label>
                    <table className="vertical-table">
                        <thead>
                        <tr className="table-name">
                            {Object.keys(staffs).map((key, staffIndex) => {
                                    var staff = staffs[key];
                                    return (
                                        <th key={staffIndex}>{staff['name']}</th>
                                    )
                                }
                            )}
                        </tr>
                        </thead>
                        <tbody>
                        <tr className="table-memo">
                            {Object.keys(staffs).map((key, staffIndex) => {
                                var staff = staffs[key];
                                return (
                                    <th key={staffIndex}>{shiftSubmissions[staff['id']] ? shiftSubmissions[staff['id']].memo : ''}</th>
                                )
                            })}
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div className="table-form-whole">
                    <form method="POST" action={`/shifts/edit`}>
                    <div className="form-start">
                        <input name="id"
                               type="hidden"
                               id="id"
                               value={shift['id']}/>
                        <input type="hidden" name="_token" value={csrfToken}/>
                        <input name="year_and_month"
                               type="hidden"
                               id="year_and_month"
                               value={selectedYearAndMonth}/>
                    </div>
                    <div className="form-buttons">
                        <input className="form-submit" type="submit" value="登録"/>
                    </div>
                    <DisplayShifts
                        shift={shift}
                        shiftSubmissions={shiftSubmissions}
                    />
                    </form>
                </div>
            </Fragment>
        );
    }
}

export default Edit;

if (document.getElementById('edit-shifts')) {
    createRoot(document.getElementById('edit-shifts')).render(<Edit/>);
}
