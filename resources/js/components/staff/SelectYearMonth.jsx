import React, {useEffect, useState} from "react";

function selectYearMonth (props) {
    const yearMonthList = props.yearMonthList;
    const callable = props.function;
    const [selectedYearAndMonth, setSelectedYearAndMonth] = useState(yearMonthList[0]);

    const handleChange = (event) => {
        setSelectedYearAndMonth(event.target.value);
    };

    useEffect(() => {
        callable(selectedYearAndMonth);
    }, [selectedYearAndMonth]);

    return (<div>
        <select className="select-year-month" value={selectedYearAndMonth} onChange={handleChange}>
            {yearMonthList.map((yearMonth, index) => (
                <option key={index} value={yearMonth}>
                    {yearMonth}
                </option>
            ))}
        </select>
    </div>
    );
}

export default selectYearMonth;
