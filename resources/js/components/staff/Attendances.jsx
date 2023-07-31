import React, {Fragment, useState, useEffect} from 'react';
import {createRoot} from "react-dom/client";
import axios from 'axios';

import InformationTable from './InformationTable';
import SelectYearMonth from './SelectYearMonth';
import {formatTime, formatMoney, formatDate} from '../common/common';

function Attendances() {
    const attendancesElement = $('#display-attendances');
    const staffId = attendancesElement.data('staff-id');
    const yearMonthList = attendancesElement.data('year-month-list');
    const [attendances, setAttendances] = useState([]);
    const selectedYearAndMonth = yearMonthList[0];
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        getAttendances(selectedYearAndMonth);
    }, [selectedYearAndMonth]);

    const getAttendances = async (yearAndMonth) => {
        await axios
            .post('/api/staffs/getAttendances', {
                staff_id: staffId,
                year_and_month: yearAndMonth,
            })
            .then(response => {
                setAttendances(response.data);
                setLoading(false);
            }).catch(() => {
                console.log('通信に失敗しました');
                setLoading(false);
            });
    }

    if (loading) {
        return <div>Loading...</div>; // ローディング中の表示
    } else {
        return (
            <Fragment>
                <SelectYearMonth
                    yearMonthList={yearMonthList}
                    function={getAttendances}
                />
                <InformationTable
                    staffId={staffId}
                />
                <div>
                    <table className="attendances-table">
                        <tr>
                            <th className="table-title" rowSpan='2'></th>
                            <th>基本労働時間</th>
                            <th>時間外労働時間</th>
                            <th>基本給</th>
                            <th>時間外給</th>
                        </tr>
                        <tr>
                            <td>{formatTime(attendances.total.total_work_time)}</td>
                            <td>{formatTime(attendances.total.total_over_work_time)}</td>
                            <td>{formatMoney(attendances.total.work_salary)}</td>
                            <td>{formatMoney(attendances.total.over_work_salary)}</td>
                        </tr>
                    </table>
                </div>
                <div>
                    <table className="attendances-table">
                        <thead>
                        <tr>
                            <th>日</th>
                            <th>出社時間</th>
                            <th>退社時間</th>
                            <th>休憩時間</th>
                            <th>基本労働時間</th>
                            <th>時間外労働時間</th>
                        </tr>
                        </thead>
                        <tbody>

                        {(attendances.attendance_details).map((attendanceDetail, index) => (
                            <tr key={index}>
                                <td>{formatDate(attendanceDetail.date)}</td>
                                <td>{attendanceDetail.arrival_time}</td>
                                <td>{attendanceDetail.leave_time}</td>
                                <td>{attendanceDetail.rest_time}</td>
                                <td>{attendanceDetail.work_time}</td>
                                <td>{attendanceDetail.over_work_time}</td>
                            </tr>
                        ))}
                        </tbody>
                    </table>
                </div>


            </Fragment>
        );
    }
}

export default Attendances;

if (document.getElementById('display-attendances')) {
    createRoot(document.getElementById('display-attendances')).render(<Attendances/>);
}
