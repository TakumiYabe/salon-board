import React, {Fragment, useState, useEffect} from 'react';
import {createRoot} from "react-dom/client";
import axios from 'axios';

function Payroll() {
    const payrollElement = $('#display-payroll');
    const staffId = payrollElement.data('staff-id');
    const yearMonthList = payrollElement.data('year-month-list');
    const [payroll, setPayroll] = useState([]);
    const [staff, setStaff] = useState();
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

    const handleChange = (event) => {
        setSelectedYearAndMonth(event.target.value);
    };

    useEffect(() => {
        getStaff(staffId);
        getPayroll(selectedYearAndMonth);
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

    const getPayroll = async (value) => {
        await axios
            .post('/api/staffs/getPayroll', {
                    staff_id: staffId,
                    year_and_month: value,
            })
            .then(response => {
                setPayroll(response.data);
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
                    <table className="payroll-table">
                        <tr>
                            <th className="table-title" rowSpan='2'></th>
                            <th>当月差引支給額</th>
                            <th>当年差引支給額</th>
                        </tr>
                        <tr>
                            <td>{formatMoney(payroll.provision.total_amount - payroll.deduction.total_amount)}</td>
                            <td>{formatMoney(payroll.year_total_provision)}</td>
                        </tr>
                    </table>
                    <table className="payroll-table">
                        <tr>
                            <th className="table-title" rowSpan='2'>勤怠</th>
                            <th>労働日数</th>
                            <th>基本労働</th>
                            <th>時間外労働</th>
                        </tr>
                        <tr>
                            <td>{payroll.attendances.work_days}</td>
                            <td>{formatTime(payroll.attendances.total_work_time)}</td>
                            <td>{formatTime(payroll.attendances.total_over_work_time)}</td>
                        </tr>
                    </table>
                    <table className="payroll-table">
                        <tr>
                            <th className="table-title" rowSpan='4'>支給</th>
                            <th>基本給</th>
                            <th>時間外給</th>
                            <th>賞与</th>
                            <th>課税対象額</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td>{formatMoney(payroll.provision.work_salary)}</td>
                            <td>{formatMoney(payroll.provision.over_work_salary)}</td>
                            <td>{formatMoney(payroll.provision.bonus)}</td>
                            <td>{formatMoney(payroll.provision.taxable_amount)}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>通勤手当</th>
                            <th></th>
                            <th></th>
                            <th>非課税対象額</th>
                            <th>総支給額</th>
                        </tr>
                        <tr>
                            <td>{formatMoney(payroll.provision.commuting_allowance)}</td>
                            <td></td>
                            <td></td>
                            <td>{formatMoney(payroll.provision.tax_exempt_amount)}</td>
                            <td>{formatMoney(payroll.provision.total_amount)}</td>
                        </tr>
                    </table>
                    <table className="payroll-table">
                        <tr>
                            <th className="table-title" rowSpan='4'>控除</th>
                            <th>健康保険</th>
                            <th>厚生年金保障</th>
                            <th>厚生年金基金</th>
                            <th>介護保険</th>
                            <th>雇用保障</th>
                            <th>社会保障合計</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td>{formatMoney(payroll.deduction.health_insurance_fee)}</td>
                            <td>{formatMoney(payroll.deduction.employee_person_insurance_fee)}</td>
                            <td></td>
                            <td></td>
                            <td>{formatMoney(payroll.deduction.employee_insurance_fee)}</td>
                            <td>{formatMoney(payroll.deduction.social_security_amount)}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>所得税</th>
                            <th>住民税</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>税額合計</th>
                            <th>総控除額</th>
                        </tr>
                        <tr>
                            <td>{formatMoney(payroll.deduction.income_tax)}</td>
                            <td>{formatMoney(payroll.deduction.resident_tax)}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{formatMoney(payroll.deduction.tax_amount)}</td>
                            <td>{formatMoney(payroll.deduction.total_amount)}</td>
                        </tr>
                    </table>
                </div>
            </Fragment>
        );
    }
}

export default Payroll;

if (document.getElementById('display-payroll')) {
    createRoot(document.getElementById('display-payroll')).render(<Payroll/>);
}
