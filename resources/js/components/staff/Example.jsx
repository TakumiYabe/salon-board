import React, {Fragment, useState, useEffect} from 'react';
import {createRoot} from "react-dom/client";
import axios from 'axios';

function Example() {

    return (
        <Fragment>
            <table>
                <thead>
                <tr>
                    <th className="table-code">社員コード</th>
                    <th className="table-name">名前(カナ)</th>
                    <th className="table-name">名前</th>
                    <th className="table-name">役職</th>
                    <th className="table-date">入社日</th>
                    <th className="table-button">勤怠</th>
                </tr>
                </thead>
                <tbody>
                {rows.map((row, i) => (
                    <tr key ={i}>
                        <td><a href = "../create">{row.code}</a></td>
                        <td>{row.name_kana}</td>
                        <td>{row.name}</td>
                        <td>{row.staff_types_name}</td>
                        <td>{row.haire_date}</td>
                        <td>勤怠</td>
                    </tr>
                ))}
                </tbody>
            </table>
        </Fragment>
    );
}

export default Example;

if (document.getElementById('app')) {
    createRoot(document.getElementById('app')).render(<Example/>);
}
