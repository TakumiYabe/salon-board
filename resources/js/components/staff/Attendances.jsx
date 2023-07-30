import React, {Fragment, useState, useEffect} from 'react';
import {createRoot} from "react-dom/client";
import axios from 'axios';

function Attendances() {
    const attendancesElement = $('#display-attendances');
    const staffId = attendancesElement.data('staff-id');
    const yearMonthList = attendancesElement.data('year-month-list');
    const [staff, setStaff] = useState([]);
    const [attendances, setAttendances] = useState([]);
    const [selectedYearAndMonth, setSelectedYearAndMonth] = useState(yearMonthList[0]);
    const [loading, setLoading] = useState(true);

    const formatTime = (seconds) => {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const formattedMinutes = (minutes < 10) ? `0${minutes}` : minutes;

        return `${hours}：${formattedMinutes}`;
    };

    const formatMoney = (number) => {
        return Math.floor(number).toLocaleString()
    }
    const formatDate = (date) => {
        return date.replace(selectedYearAndMonth + '-', '');
    }

    const handleChange = (event) => {
        setSelectedYearAndMonth(event.target.value);
    };

    useEffect(() => {
        getStaff(staffId);
        getAttendances(selectedYearAndMonth);
    }, [selectedYearAndMonth]);

    const getStaff = async (value) => {
        await axios
            .post('/api/staffs/getStaff', {
                staff_id: staffId,
            })
            .then(response => {
                setStaff(response.data);
            }).catch(() => {
                console.log('通信に失敗しました');
            });
    }

    const getAttendances = async (value) => {
        await axios
            .post('/api/staffs/getAttendances', {
                staff_id: staffId,
                year_and_month: value,
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
                <div>
                    <select className="select-year-month" value={selectedYearAndMonth} onChange={handleChange}>
                        {yearMonthList.map((yearMonth, index) => (
                            <option key={index} value={yearMonth}>
                                {yearMonth}
                            </option>
                        ))}
                    </select>
                </div>
                <div>
                    <table className="staff-information-table">
                        <tr>
                            <th className="table-title" rowSpan='2'>社員情報</th>
                            <th>社員コード</th>
                            <th>役職</th>
                            <th className="table-name">氏名</th>
                        </tr>
                        <tr>
                            <td>{staff.code}</td>
                            <td>{staff.staff_types.name}</td>
                            <td className="table-name">{staff.name}</td>
                        </tr>
                    </table>
                </div>
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
