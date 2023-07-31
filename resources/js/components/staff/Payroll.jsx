import React, {Fragment, useState, useEffect} from 'react';
import {createRoot} from "react-dom/client";
import axios from 'axios';

import InformationTable from "./InformationTable.jsx";
import SelectYearMonth from "./SelectYearMonth.jsx";
import {formatTime, formatMoney} from '../common/common';

function Payroll() {
    const payrollElement = $('#display-payroll');
    const staffId = payrollElement.data('staff-id');
    const yearMonthList = payrollElement.data('year-month-list');
    const [payroll, setPayroll] = useState([]);
    const selectedYearAndMonth = yearMonthList[0];
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        getPayroll(selectedYearAndMonth);
    }, [selectedYearAndMonth]);

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
                <SelectYearMonth
                    yearMonthList={yearMonthList}
                    function={getPayroll}
                />
                <InformationTable
                    staffId={staffId}
                />
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
