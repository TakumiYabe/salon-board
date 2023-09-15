import React, {useEffect, useState} from 'react';
import axios from "axios";

function InformationTable(props) {
    const staffId = props.staffId;
    const [staff, setStaff] = useState();
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        getStaff(staffId);
    }, [staffId]);

    const getStaff = async (staffId) => {
        await axios
            .post('/api/staffs/get', {
                staff_id: staffId,
            })
            .then(response => {
                setStaff(response.data);
                setLoading(false);
            }).catch(() => {
                console.log('通信に失敗しました');
            });
    }

    if (loading) {
        return <div>Loading...</div>; // ローディング中の表示
    } else {
        return (
            <div>
                <table className="staff-table">
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
        );
    }
}

export default InformationTable;
