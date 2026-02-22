@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Present Today Card -->
        <a href="/attendance" class="block bg-white rounded-lg shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all hover:scale-105 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Present Today</p>
                    <p class="text-2xl font-bold text-slate-900 mt-2">{{ $presentToday }}</p>
                    <p class="text-sm text-green-600 mt-2">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ $totalEmployees > 0 ? round($presentToday / $totalEmployees * 100, 1) : 0 }}% of staff
                        </span>
                    </p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Leave Pending Card -->
        <a href="{{ route('leaves.manage') }}" class="block bg-white rounded-lg shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all hover:scale-105 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Leave Pending</p>
                    <p class="text-2xl font-bold text-slate-900 mt-2">{{ $leavePending }}</p>
                    <p class="text-sm text-orange-600 mt-2">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $leavePending > 0 ? 'Action needed' : 'All clear' }}
                        </span>
                    </p>
                </div>
                <div class="p-3 bg-orange-100 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Claims Pending Card -->
        <a href="/claims-reimbursement" class="block bg-white rounded-lg shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all hover:scale-105 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Claims Pending</p>
                    <p class="text-2xl font-bold text-slate-900 mt-2">{{ $claimsPending }}</p>
                    <p class="text-sm text-amber-600 mt-2">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $claimsPending > 5 ? 'High priority' : 'Normal' }}
                        </span>
                    </p>
                </div>
                <div class="p-3 bg-amber-100 rounded-lg">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Total Shifts Card -->
        <a href="/schedule-management" class="block bg-white rounded-lg shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all hover:scale-105 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Total Shifts</p>
                    <p class="text-2xl font-bold text-slate-900 mt-2">{{ $totalShifts }}</p>
                    <p class="text-sm text-indigo-600 mt-2">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Active today
                        </span>
                    </p>
                </div>
                <div class="p-3 bg-indigo-100 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </a>
    </div>

    <!-- Calendar and Events Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Beautiful Real-time Calendar -->
        <div class="lg:col-span-2 bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-lg border border-blue-100 p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Calendar 2026</h3>
                <div class="flex items-center gap-3">
                    <button id="prevMonth" class="p-3 bg-white/80 backdrop-blur-sm hover:bg-white rounded-xl transition-all hover:shadow-md">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <span id="currentMonth" class="text-lg font-semibold text-slate-800 min-w-[150px] text-center bg-white/60 backdrop-blur-sm px-4 py-2 rounded-xl"></span>
                    <button id="nextMonth" class="p-3 bg-white/80 backdrop-blur-sm hover:bg-white rounded-xl transition-all hover:shadow-md">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 gap-2 mb-6">
                <!-- Day Headers -->
                <div class="text-center text-xs font-bold text-blue-600 py-3 bg-gradient-to-b from-blue-100 to-transparent rounded-lg">SUN</div>
                <div class="text-center text-xs font-bold text-blue-600 py-3 bg-gradient-to-b from-blue-100 to-transparent rounded-lg">MON</div>
                <div class="text-center text-xs font-bold text-blue-600 py-3 bg-gradient-to-b from-blue-100 to-transparent rounded-lg">TUE</div>
                <div class="text-center text-xs font-bold text-blue-600 py-3 bg-gradient-to-b from-blue-100 to-transparent rounded-lg">WED</div>
                <div class="text-center text-xs font-bold text-blue-600 py-3 bg-gradient-to-b from-blue-100 to-transparent rounded-lg">THU</div>
                <div class="text-center text-xs font-bold text-blue-600 py-3 bg-gradient-to-b from-blue-100 to-transparent rounded-lg">FRI</div>
                <div class="text-center text-xs font-bold text-blue-600 py-3 bg-gradient-to-b from-blue-100 to-transparent rounded-lg">SAT</div>
                
                <!-- Calendar Days -->
                <div id="calendarDays" class="col-span-7 grid grid-cols-7 gap-2">
                    <!-- Days will be populated by JavaScript -->
                </div>
            </div>
            
            <!-- Real-time Clock -->
            <div class="mt-6 pt-6 border-t border-blue-200">
                <div class="flex items-center justify-between bg-white/60 backdrop-blur-sm rounded-xl p-4">
                    <div>
                        <p class="text-sm font-medium text-blue-600 mb-1">🇵🇭 Philippines Time</p>
                        <p id="philippineTime" class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">--:--:--</p>
                    </div>
                    <div class="text-right">
                        <p id="philippineDate" class="text-sm font-medium text-slate-700 mb-1">Loading...</p>
                        <p id="timezone" class="text-xs text-slate-500">PST (UTC+8)</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- All 2026 Events & Holidays -->
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl shadow-lg border border-purple-200 p-6">
            <h3 class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 mb-4">🎉 Events & Holidays</h3>
            <div class="mb-4 space-y-3">
                <div class="flex gap-2">
                    <select id="yearSelector" class="flex-1 px-3 py-2 bg-white/80 backdrop-blur-sm border border-purple-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
                        <!-- Years will be populated by JavaScript -->
                    </select>
                    <select id="eventFilter" class="flex-1 px-3 py-2 bg-white/80 backdrop-blur-sm border border-purple-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
                        <option value="all">All Events</option>
                        <option value="holiday">Holidays</option>
                        <option value="seasonal">Seasonal</option>
                        <option value="cultural">Cultural</option>
                    </select>
                </div>
                <button id="refreshEvents" class="w-full px-3 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh Events
                </button>
            </div>
            <div id="eventsList" class="space-y-3 max-h-96 overflow-y-auto">
                <!-- Events will be populated by JavaScript -->
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-purple-400 mx-auto mb-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <p class="text-purple-600">Loading events...</p>
                </div>
            </div>
        </div>
    </div>

    

</div>

<script>
// Calendar functionality
let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();

function updateCalendar() {
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'];
    
    document.getElementById('currentMonth').textContent = 
        `${monthNames[currentMonth]} ${currentYear}`;
    
    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    const today = new Date();
    
    let calendarHTML = '';
    
    // Empty cells for days before month starts
    for (let i = 0; i < firstDay; i++) {
        calendarHTML += '<div class="p-3"></div>';
    }
    
    // Days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const isToday = today.getFullYear() === currentYear && 
                       today.getMonth() === currentMonth && 
                       today.getDate() === day;
        const isWeekend = new Date(currentYear, currentMonth, day).getDay() === 0 || 
                         new Date(currentYear, currentMonth, day).getDay() === 6;
        
        // Check if this day has events
        const currentDateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const dayEvents = allEvents.filter(event => event.date === currentDateStr);
        const hasEvent = dayEvents.length > 0;
        
        let classes = 'p-2 text-center rounded-xl cursor-pointer transition-all transform hover:scale-105 min-h-[60px] flex flex-col items-center justify-center ';
        if (isToday) {
            classes += 'bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold shadow-lg';
        } else if (hasEvent) {
            classes += 'bg-gradient-to-r from-pink-100 to-purple-100 text-purple-800 font-semibold border-2 border-purple-300';
        } else if (isWeekend) {
            classes += 'bg-gradient-to-b from-blue-50 to-transparent text-blue-600 hover:from-blue-100';
        } else {
            classes += 'hover:bg-gradient-to-b hover:from-blue-50 hover:to-transparent text-slate-800';
        }
        
        let dayContent = `<div class="text-lg font-bold">${day}</div>`;
        
        // Add event labels if there are events
        if (hasEvent && dayEvents.length > 0) {
            const eventNames = dayEvents.slice(0, 2).map(event => {
                const maxLength = 8;
                const shortName = event.name.length > maxLength ? 
                    event.name.substring(0, maxLength) + '...' : event.name;
                return `<div class="text-xs mt-1 truncate">${shortName}</div>`;
            }).join('');
            
            const moreText = dayEvents.length > 2 ? 
                `<div class="text-xs opacity-75">+${dayEvents.length - 2} more</div>` : '';
            
            dayContent += eventNames + moreText;
        }
        
        calendarHTML += `<div class="${classes}" title="${dayEvents.map(e => e.name).join(', ')}">${dayContent}</div>`;
    }
    
    document.getElementById('calendarDays').innerHTML = calendarHTML;
}

function updatePhilippineTime() {
    const now = new Date();
    // Convert to Philippines Time (UTC+8)
    const philippinesTime = new Date(now.toLocaleString("en-US", {timeZone: "Asia/Manila"}));
    
    const timeString = philippinesTime.toLocaleTimeString('en-US', {
        hour12: false,
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
    
    const dateString = philippinesTime.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    document.getElementById('philippineTime').textContent = timeString;
    document.getElementById('philippineDate').textContent = dateString;
}

// Global variables
let selectedYear = new Date().getFullYear();
let allEvents = [];

// Initialize year selector
function initializeYearSelector() {
    const yearSelector = document.getElementById('yearSelector');
    const currentYear = new Date().getFullYear();
    
    // Add years from current year to 5 years ahead
    for (let year = currentYear; year <= currentYear + 5; year++) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        if (year === selectedYear) {
            option.selected = true;
        }
        yearSelector.appendChild(option);
    }
}

// Fetch Philippine holidays from API
async function fetchPhilippineHolidays(year) {
    try {
        // Using a free holiday API (you may need to replace with a more comprehensive one)
        const response = await fetch(`https://date.nager.at/api/v3/PublicHolidays/${year}/PH`);
        const holidays = await response.json();
        
        return holidays.map(holiday => ({
            date: holiday.date,
            name: holiday.localName || holiday.name,
            type: 'holiday',
            emoji: '🇵🇭',
            source: 'api'
        }));
    } catch (error) {
        console.log('API failed, using fallback data for Philippines holidays');
        return getFallbackPhilippineHolidays(year);
    }
}

// Fallback Philippine holidays if API fails
function getFallbackPhilippineHolidays(year) {
    const holidays = [
        { date: `${year}-01-01`, name: "New Year's Day", type: 'holiday', emoji: '🎊' },
        { date: `${year}-04-09`, name: 'Day of Valor', type: 'holiday', emoji: '🇵🇭' },
        { date: `${year}-05-01`, name: 'Labor Day', type: 'holiday', emoji: '🇵🇭' },
        { date: `${year}-06-12`, name: 'Independence Day', type: 'holiday', emoji: '🇵🇭' },
        { date: `${year}-08-25`, name: 'National Heroes Day', type: 'holiday', emoji: '🇵🇭' },
        { date: `${year}-11-30`, name: 'Bonifacio Day', type: 'holiday', emoji: '🇵🇭' },
        { date: `${year}-12-25`, name: 'Christmas Day', type: 'holiday', emoji: '🎅' },
        { date: `${year}-12-30`, name: 'Rizal Day', type: 'holiday', emoji: '🇵🇭' }
    ];
    
    // Calculate movable holidays
    const edsaDate = calculateEDSADay(year);
    if (edsaDate) {
        holidays.push({ date: edsaDate, name: 'EDSA People Power Revolution', type: 'holiday', emoji: '🇵🇭' });
    }
    
    const holyWeek = calculateHolyWeek(year);
    if (holyWeek) {
        holidays.push(
            { date: holyWeek.maundyThursday, name: 'Maundy Thursday', type: 'holiday', emoji: '✝️' },
            { date: holyWeek.goodFriday, name: 'Good Friday', type: 'holiday', emoji: '✝️' },
            { date: holyWeek.blackSaturday, name: 'Black Saturday', type: 'holiday', emoji: '✝️' }
        );
    }
    
    return holidays;
}

// Calculate EDSA Day (February 25)
function calculateEDSADay(year) {
    return `${year}-02-25`;
}

// Calculate Holy Week dates
function calculateHolyWeek(year) {
    // More accurate Easter calculation using Computus algorithm
    const a = year % 19;
    const b = Math.floor(year / 100);
    const c = year % 100;
    const d = Math.floor(b / 4);
    const e = b % 4;
    const f = Math.floor((b + 8) / 25);
    const g = Math.floor((b - f + 1) / 3);
    const h = (19 * a + b - d - g + 15) % 30;
    const i = Math.floor(c / 4);
    const k = c % 4;
    const l = (32 + 2 * e + 2 * i - h - k) % 7;
    const m = Math.floor((a + 11 * h + 22 * l) / 451);
    const month = Math.floor((h + l - 7 * m + 114) / 31);
    const day = ((h + l - 7 * m + 114) % 31) + 1;
    
    const easterSunday = new Date(year, month - 1, day);
    const goodFriday = new Date(easterSunday);
    goodFriday.setDate(easterSunday.getDate() - 2);
    const maundyThursday = new Date(easterSunday);
    maundyThursday.setDate(easterSunday.getDate() - 3);
    const blackSaturday = new Date(easterSunday);
    blackSaturday.setDate(easterSunday.getDate() - 1);
    
    return {
        maundyThursday: formatDate(maundyThursday),
        goodFriday: formatDate(goodFriday),
        blackSaturday: formatDate(blackSaturday)
    };
}

// Get seasonal events
function getSeasonalEvents(year) {
    const events = [
        { date: `${year}-02-14`, name: "Valentine's Day", type: 'seasonal', emoji: '💝' },
        { date: `${year}-03-20`, name: 'Spring Equinox', type: 'seasonal', emoji: '🌸' },
        { date: `${year}-04-01`, name: "April Fools' Day", type: 'seasonal', emoji: '😄' },
        { date: `${year}-06-21`, name: 'Summer Solstice', type: 'seasonal', emoji: '☀️' },
        { date: `${year}-09-22`, name: 'Autumn Equinox', type: 'seasonal', emoji: '🍂' },
        { date: `${year}-10-31`, name: 'Halloween', type: 'seasonal', emoji: '🎃' },
        { date: `${year}-12-24`, name: 'Christmas Eve', type: 'seasonal', emoji: '🎄' },
        { date: `${year}-12-31`, name: "New Year's Eve", type: 'seasonal', emoji: '🍾' }
    ];
    
    // Mother's Day (Second Sunday in May)
    const mothersDay = calculateMothersDay(year);
    if (mothersDay) events.push({ date: mothersDay, name: "Mother's Day", type: 'seasonal', emoji: '💐' });
    
    // Father's Day (Third Sunday in June)
    const fathersDay = calculateFathersDay(year);
    if (fathersDay) events.push({ date: fathersDay, name: "Father's Day", type: 'seasonal', emoji: '👔' });
    
    return events;
}

// Calculate Mother's Day (Second Sunday in May)
function calculateMothersDay(year) {
    const firstMay = new Date(year, 4, 1);
    const firstSunday = new Date(firstMay);
    while (firstSunday.getDay() !== 0) {
        firstSunday.setDate(firstSunday.getDate() + 1);
    }
    const mothersDay = new Date(firstSunday);
    mothersDay.setDate(firstSunday.getDate() + 7);
    return formatDate(mothersDay);
}

// Calculate Father's Day (Third Sunday in June)
function calculateFathersDay(year) {
    const firstJune = new Date(year, 5, 1);
    const firstSunday = new Date(firstJune);
    while (firstSunday.getDay() !== 0) {
        firstSunday.setDate(firstSunday.getDate() + 1);
    }
    const fathersDay = new Date(firstSunday);
    fathersDay.setDate(firstSunday.getDate() + 14);
    return formatDate(fathersDay);
}

// Get cultural events
function getCulturalEvents(year) {
    const events = [
        { date: `${year}-01-29`, name: 'Chinese New Year', type: 'cultural', emoji: '🧧' },
        { date: `${year}-03-17`, name: "St. Patrick's Day", type: 'cultural', emoji: '🍀' },
        { date: `${year}-04-20`, name: 'Easter Sunday', type: 'cultural', emoji: '🐰' },
        { date: `${year}-07-04`, name: 'Independence Day (US)', type: 'cultural', emoji: '🇺🇸' },
        { date: `${year}-11-01`, name: "All Saints' Day", type: 'cultural', emoji: '⚰️' },
        { date: `${year}-11-02`, name: "All Souls' Day", type: 'cultural', emoji: '🙏' },
        { date: `${year}-11-11`, name: 'Veterans Day', type: 'cultural', emoji: '🇺🇸' },
        { date: `${year}-12-08`, name: 'Feast of the Immaculate Conception', type: 'cultural', emoji: '⭐' },
        { date: `${year}-12-16`, name: 'Simbang Gabi Starts', type: 'cultural', emoji: '🌟' }
    ];
    
    // Thanksgiving (Fourth Thursday in November)
    const thanksgiving = calculateThanksgiving(year);
    if (thanksgiving) events.push({ date: thanksgiving, name: 'Thanksgiving', type: 'cultural', emoji: '🦃' });
    
    return events;
}

// Calculate Thanksgiving (Fourth Thursday in November)
function calculateThanksgiving(year) {
    const firstNov = new Date(year, 10, 1);
    const firstThursday = new Date(firstNov);
    while (firstThursday.getDay() !== 4) {
        firstThursday.setDate(firstThursday.getDate() + 1);
    }
    const thanksgiving = new Date(firstThursday);
    thanksgiving.setDate(firstThursday.getDate() + 21);
    return formatDate(thanksgiving);
}

// Format date to YYYY-MM-DD
function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// Fetch all events for the selected year
async function fetchEventsForYear(year) {
    try {
        const [holidays, seasonal, cultural] = await Promise.all([
            fetchPhilippineHolidays(year),
            Promise.resolve(getSeasonalEvents(year)),
            Promise.resolve(getCulturalEvents(year))
        ]);
        
        allEvents = [...holidays, ...seasonal, ...cultural];
        allEvents.sort((a, b) => new Date(a.date) - new Date(b.date));
        
        updateEventsList();
        updateCalendar();
    } catch (error) {
        console.error('Error fetching events:', error);
        showError('Failed to load events. Please try again.');
    }
}

// Show error message
function showError(message) {
    const eventsList = document.getElementById('eventsList');
    eventsList.innerHTML = `
        <div class="text-center py-8">
            <svg class="w-12 h-12 text-red-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-red-600">${message}</p>
        </div>
    `;
}

function updateEventsList() {
    const filter = document.getElementById('eventFilter')?.value || 'all';
    const today = new Date();
    let filteredEvents = [...allEvents];
    
    // Apply filter
    if (filter !== 'all') {
        filteredEvents = filteredEvents.filter(event => event.type === filter);
    }
    
    // Sort by date
    filteredEvents.sort((a, b) => new Date(a.date) - new Date(b.date));
    
    // Generate HTML
    let eventsHTML = '';
    
    if (filteredEvents.length === 0) {
        eventsHTML = `
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-purple-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-purple-600">No events found for ${selectedYear}</p>
            </div>
        `;
    } else {
        filteredEvents.forEach(event => {
            const eventDate = new Date(event.date);
            const daysUntil = Math.ceil((eventDate - today) / (1000 * 60 * 60 * 24));
            const isPast = daysUntil < 0;
            const isToday = daysUntil === 0;
            
            let typeBg = '';
            let typeText = '';
            
            if (event.type === 'holiday') {
                typeBg = 'bg-red-100 text-red-800';
                typeText = 'Holiday';
            } else if (event.type === 'cultural') {
                typeBg = 'bg-blue-100 text-blue-800';
                typeText = 'Cultural';
            } else if (event.type === 'seasonal') {
                typeBg = 'bg-green-100 text-green-800';
                typeText = 'Seasonal';
            }
            
            eventsHTML += `
                <div class="p-3 rounded-xl bg-white/80 backdrop-blur-sm border border-purple-200 hover:bg-white hover:shadow-md transition-all cursor-pointer">
                    <div class="flex items-start gap-3">
                        <div class="text-2xl">${event.emoji}</div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-900">${event.name}</p>
                            <p class="text-sm text-slate-600 mt-1">
                                ${eventDate.toLocaleDateString('en-US', { 
                                    weekday: 'short', 
                                    month: 'short', 
                                    day: 'numeric',
                                    year: 'numeric'
                                })}
                            </p>
                            ${event.source === 'api' ? '<p class="text-xs text-blue-500 mt-1">📡 From API</p>' : ''}
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            <span class="px-2 py-1 text-xs font-medium ${typeBg} rounded-full">
                                ${typeText}
                            </span>
                            <p class="text-xs text-slate-500">
                                ${isPast ? 'Past' : isToday ? '🎉 Today' : `${daysUntil} days`}
                            </p>
                        </div>
                    </div>
                </div>
            `;
        });
    }
    
    document.getElementById('eventsList').innerHTML = eventsHTML;
}

// Event listeners
document.getElementById('prevMonth').addEventListener('click', () => {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    updateCalendar();
});

document.getElementById('nextMonth').addEventListener('click', () => {
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    updateCalendar();
});

// Event filter listener
document.getElementById('eventFilter')?.addEventListener('change', updateEventsList);

// Year selector listener
document.getElementById('yearSelector')?.addEventListener('change', (e) => {
    selectedYear = parseInt(e.target.value);
    fetchEventsForYear(selectedYear);
});

// Refresh button listener
document.getElementById('refreshEvents')?.addEventListener('click', () => {
    fetchEventsForYear(selectedYear);
});

// Initialize
initializeYearSelector();
fetchEventsForYear(selectedYear);
updatePhilippineTime();

// Update time every second
setInterval(updatePhilippineTime, 1000);

// Update dashboard stats function
async function updateDashboardStats() {
    try {
        const response = await fetch('/api/dashboard/stats');
        const stats = await response.json();
        
        // Update Present Today card
        const presentCard = document.querySelector('.grid .bg-white:nth-child(2) .text-2xl');
        if (presentCard) presentCard.textContent = stats.presentToday;
        
        // Update Leave Pending card
        const leaveCard = document.querySelector('.grid .bg-white:nth-child(3) .text-2xl');
        if (leaveCard) leaveCard.textContent = stats.leavePending;
        
        // Update Claims Pending card
        const claimsCard = document.querySelector('.grid .bg-white:nth-child(4) .text-2xl');
        if (claimsCard) claimsCard.textContent = stats.claimsPending;
        
        // Update Total Shifts card
        const shiftsCard = document.querySelector('.grid .bg-white:nth-child(5) .text-2xl');
        if (shiftsCard) shiftsCard.textContent = stats.totalShifts;
        
    } catch (error) {
        console.log('Dashboard stats update failed:', error);
    }
}
</script>
@endsection