import React, {Fragment, useState, useEffect} from 'react';
import {createRoot} from "react-dom/client";
import axios from 'axios';

import InformationTable from './InformationTable';
import SelectYearMonth from "../common/SelectYearMonth.jsx";
import {formatTime, formatMoney, formatDate} from '../common/common';

function ProvisionAndDeduction() {
    const element = $('#display-provision-and-deduction');
    const staffId = element.data('staff-id');
    const yearMonthList = element.data('year-month-list');
    const url = element.data('url');
    const selectedYearAndMonth = yearMonthList[0];
    const [loading, setLoading] = useState(true);
    const [formData, setFormData] = useState({
        staff_id: staffId,
        year_and_month: selectedYearAndMonth,
        work_salary: 0,
        over_work_salary: 0,
        bonus: 0,
        commuting_allowance: 0,
        health_insurance_fee: 0,
        employee_person_insurance_fee: 0,
        employee_insurance_fee: 0,
        income_tax: 0,
        resident_tax: 0,
    });

    useEffect(() => {
        getProvisionAndDeduction(selectedYearAndMonth);
    }, [selectedYearAndMonth]);

    // 入力値変更
    const editChange = (e) => {
        formData[e.target.name] = e.target.value;
        setFormData(Object.assign({}, formData));
    }

    // 送信ハンドル
    const handleSubmit = (event) => {
        const inputAmount = $('.input-money');

        inputAmount.each(function (index, input) {
            input = $(input);
            const formatValue = input.val().replace(/,/g, '');
            input.val(formatValue)
        })
    };

    const getProvisionAndDeduction = async (yearAndMonth) => {
        await axios
            .post('/api/staffs/getProvisionAndDeduction', {
                staff_id: staffId,
                year_and_month: yearAndMonth,
            })
            .then(response => {
                setFormData({
                    staff_id: staffId,
                    year_and_month: yearAndMonth,
                    work_salary: response.data.provision.work_salary,
                    over_work_salary: response.data.provision.over_work_salary,
                    bonus: response.data.provision.bonus,
                    commuting_allowance: response.data.provision.commuting_allowance,
                    health_insurance_fee: response.data.deduction.health_insurance_fee,
                    employee_person_insurance_fee: response.data.deduction.employee_person_insurance_fee,
                    employee_insurance_fee: response.data.deduction.employee_insurance_fee,
                    income_tax: response.data.deduction.income_tax,
                    resident_tax: response.data.deduction.resident_tax,
                });
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
                    function={getProvisionAndDeduction}
                />
                <InformationTable
                    staffId={staffId}
                />
                <div className="form-whole">
                    <form method="POST" action={url} onSubmit={handleSubmit}>
                        <div className="form-start">
                            <input type="hidden" name="_token" value={csrfToken}/>
                            <input name="year_and_month"
                                   type="hidden"
                                   id="year_and_month"
                                   value={formData.year_and_month}/>
                        </div>
                        <div className="form-buttons">
                            <input className="form-submit" type="submit" value="登録"/>
                        </div>
                        <div className="form-input">
                            <div className="form-row display-flex">
                                <div className="form-block">
                                    <h3>支給</h3>
                                    <div className="form-element">
                                        <label className="form-label">基本給</label>
                                        <input name="work_salary"
                                               type="text"
                                               className="input-money"
                                               id="work_salary"
                                               value={formatMoney(formData.work_salary ?? 0)}
                                               onChange={editChange}/>
                                    </div>
                                    <div className="form-element">
                                        <label className="form-label">時間外給</label>
                                        <input name="over_work_salary"
                                               type="text"
                                               className="input-money"
                                               id="over_work_salary"
                                               value={formatMoney(formData.over_work_salary ?? 0)}
                                               onChange={editChange}/>
                                    </div>
                                    <div className="form-element">
                                        <label className="form-label">賞与</label>
                                        <input name="bonus"
                                               type="text"
                                               className="input-money"
                                               id="bonus"
                                               value={formatMoney(formData.bonus ?? 0)}
                                               onChange={editChange}/>
                                    </div>
                                    <div className="form-element">
                                        <label className="form-label">通勤手当</label>
                                        <input name="commuting_allowance"
                                               type="text"
                                               className="input-money"
                                               id="commuting_allowance"
                                               value={formatMoney(formData.commuting_allowance ?? 0)}
                                               onChange={editChange}/>
                                    </div>
                                </div>
                                <div className="form-block">
                                    <h3>控除</h3>
                                    <div className="form-element">
                                        <label className="form-label">健康保険</label>
                                        <input name="health_insurance_fee"
                                               type="text"
                                               className="input-money"
                                               id="health_insurance_fee"
                                               value={formatMoney(formData.health_insurance_fee ?? 0)}
                                               onChange={editChange}/>
                                    </div>
                                    <div className="form-element">
                                        <label className="form-label">厚生年金保険</label>
                                        <input name="employee_person_insurance_fee"
                                               type="text"
                                               className="input-money"
                                               id="employee_person_insurance_fee"
                                               value={formatMoney(formData.employee_person_insurance_fee ?? 0)}
                                               onChange={editChange}/>
                                    </div>
                                    <div className="form-element"><label className="form-label">雇用保険</label>
                                        <input name="employee_insurance_fee"
                                               type="text"
                                               className="input-money"
                                               id="employee_insurance_fee"
                                               value={formatMoney(formData.employee_insurance_fee ?? 0)}
                                               onChange={editChange}/>
                                    </div>
                                    <div className="form-element">
                                        <label className="form-label">所得税</label>
                                        <input name="income_tax"
                                               type="text"
                                               className="input-money"
                                               id="income_tax"
                                               value={formatMoney(formData.income_tax ?? 0)}
                                               onChange={editChange}/>
                                    </div>
                                    <div className="form-element">
                                        <label className="form-label">住民税</label>
                                        <input name="resident_tax"
                                               type="text"
                                               className="input-money"
                                               id="resident_tax"
                                               value={formatMoney(formData.resident_tax ?? 0)}
                                               onChange={editChange}/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </Fragment>
        );
    }
}

export default ProvisionAndDeduction;

if (document.getElementById('display-provision-and-deduction')) {
    createRoot(document.getElementById('display-provision-and-deduction')).render(<ProvisionAndDeduction/>);
}
