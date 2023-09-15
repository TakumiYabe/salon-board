export const formatTime = (seconds) => {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const formattedMinutes = (minutes < 10) ? `0${minutes}` : minutes;

    return `${hours}：${formattedMinutes}`;
};

export const formatMoney = (number) => {
    return Math.floor(number);
}

export const formatDate = (date) => {
    return date.substring(8);
}

export const formatDayWeek = (date) => {
    console.log(date);
    return date
}
