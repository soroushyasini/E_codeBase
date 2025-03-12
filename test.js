// Function to convert Gregorian to Jalali
function gregorianToJalali(gy, gm, gd) {
    var g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
    var jy = gy <= 1600 ? 0 : 979;
    gy -= gy <= 1600 ? 621 : 1600;
    var gy2 = gm > 2 ? gy + 1 : gy;
    var days =
        365 * gy +
        parseInt((gy2 + 3) / 4) -
        parseInt((gy2 + 99) / 100) +
        parseInt((gy2 + 399) / 400) -
        80 +
        gd +
        g_d_m[gm - 1];
    jy += 33 * parseInt(days / 12053);
    days %= 12053;
    jy += 4 * parseInt(days / 1461);
    days %= 1461;
    jy += parseInt((days - 1) / 365);

    if (days > 365) days = (days - 1) % 365;

    var jm = days < 186 ? 1 + parseInt(days / 31) : 7 + parseInt((days - 186) / 30);
    var jd = 1 + (days < 186 ? days % 31 : (days - 186) % 30);

    return [jy, jm, jd];
}

// Function to pad numbers with leading zeros
function pad(number) {
    return number < 10 ? "0" + number : number;
}

// Function to update table dates
function updateTableDates() {
    const dateCells = document.querySelectorAll(".xcrud-list tbody tr td:nth-child(2)");

    dateCells.forEach((cell) => {
        if (cell.getAttribute("data-converted") === "true") return;

        const originalDateStr = cell.textContent.trim();
        try {
            const [datePart, timePart] = originalDateStr.split(" ");
            const [year, month, day] = datePart.split("-").map(Number);
            const jalaliDate = gregorianToJalali(year, month, day);
            const formattedJalaliDate = `${jalaliDate[0]}/${pad(jalaliDate[1])}/${pad(jalaliDate[2])}`;

            cell.textContent = `${formattedJalaliDate} ${timePart}`;
            cell.setAttribute("data-converted", "true");
        } catch (e) {
            console.error("Error converting date:", originalDateStr, e);
        }
    });
}

// Function to reinitialize observer after AJAX updates
function observeTableChanges() {
    const target = document.querySelector(".xcrud-list tbody");
    if (!target) {
        console.warn("Table body not found.");
        return;
    }

    // Disconnect any existing observer before creating a new one
    if (window.dateObserver) {
        window.dateObserver.disconnect();
    }

    window.dateObserver = new MutationObserver(() => {
        updateTableDates();
    });

    window.dateObserver.observe(target, { childList: true, subtree: true });
}

// Run initially when document is ready
$(document).ready(function () {
    updateTableDates();
    observeTableChanges();

    // Listen for AJAX requests completing
    $(document).ajaxComplete(function () {
        updateTableDates();
        observeTableChanges();
    });
});
