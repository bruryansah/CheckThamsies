import { computed } from 'vue';

export function useAttendance(jadwalData, absensiData) {
    // Helper functions
    const normalizeHari = (h) => (typeof h === 'string' ? h.trim().toLowerCase() : '');
    
    const getTodayHariLower = () => ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'][new Date().getDay()];
    
    const parseTimeToMinutes = (t) => {
        if (!t || typeof t !== 'string') return null;
        const m = t.match(/^(\d{1,2}):(\d{2})/);
        if (!m) return null;
        return parseInt(m[1], 10) * 60 + parseInt(m[2], 10);
    };

    const formatHari = (hari) => {
        const hariMap = {
            senin: 'Senin',
            selasa: 'Selasa',
            rabu: 'Rabu',
            kamis: 'Kamis',
            jumat: 'Jumat',
            sabtu: 'Sabtu',
            minggu: 'Minggu',
        };
        return hariMap[normalizeHari(hari)] || hari;
    };

    const processAttendanceStatus = (attendance) => {
        const mapel = attendance.subject || attendance.mata_pelajaran;
        const kelas = attendance.class || attendance.nama_kelas;
        const hari = attendance.hari;

        const jadwal = jadwalData.value.find((j) => j.mata_pelajaran === mapel && j.nama_kelas === kelas && j.hari === hari);

        let displayStatus = attendance.status || 'Belum Absen';

        if (jadwal) {
            // Late if present > 10 minutes after start
            if (displayStatus.toLowerCase() === 'hadir') {
                const attendanceMinutes = parseTimeToMinutes(attendance.time || attendance.waktu);
                const scheduleMinutes = parseTimeToMinutes(jadwal.jam_mulai);
                if (attendanceMinutes != null && scheduleMinutes != null) {
                    const lateThreshold = scheduleMinutes + 10;
                    if (attendanceMinutes > lateThreshold) {
                        displayStatus = 'Terlambat';
                    }
                }
            }

            // Auto ALFA if not checked in 45 minutes after start, same day
            const todayHari = getTodayHariLower();
            const isSameDay = normalizeHari(jadwal.hari) === normalizeHari(todayHari);
            const hasNoScan = parseTimeToMinutes(attendance.time || attendance.waktu) == null;
            if (isSameDay && hasNoScan && (displayStatus.toLowerCase() === 'belum absen' || displayStatus.toLowerCase() === '')) {
                const now = new Date();
                const nowMinutes = now.getHours() * 60 + now.getMinutes();
                const scheduleMinutes = parseTimeToMinutes(jadwal.jam_mulai);
                if (scheduleMinutes != null && nowMinutes > scheduleMinutes + 45) {
                    displayStatus = 'Alfa';
                }
            }
        }

        return {
            ...attendance,
            displayStatus,
            originalStatus: attendance.status,
            scheduleInfo: jadwal || null,
        };
    };

    const getAttendanceStatusClass = (student) => {
        let status = (student.displayStatus || student.status || '').toLowerCase().trim();
        status = status.replace(/\s*-.*$/, '');

        if (status === 'hadir') {
            return 'bg-green-100 text-green-800 border border-green-300';
        } else if (status === 'terlambat' || status === 'alfa' || status === 'alpha' || status === 'tidak hadir') {
            return 'bg-red-100 text-red-800 border border-red-300';
        } else if (status === 'izin' || status === 'sakit') {
            return 'bg-yellow-100 text-yellow-800 border border-yellow-300';
        } else if (status === 'belum absen' || status === '') {
            return 'bg-gray-100 text-gray-800 border border-gray-300';
        } else {
            return 'bg-gray-100 text-gray-800 border border-gray-300';
        }
    };

    // Filtered attendance data
    const filteredAbsensiData = computed(() => {
        const jadwalArr = Array.isArray(jadwalData.value) ? jadwalData.value : [];
        const absensiArr = Array.isArray(absensiData.value) ? absensiData.value : [];
        if (jadwalArr.length === 0 || absensiArr.length === 0) return [];

        const studentsByClass = new Map();
        for (const a of absensiArr) {
            const className = a.class || a.nama_kelas;
            const name = a.name || a.nama_siswa;
            if (!className || !name) continue;
            if (!studentsByClass.has(className)) studentsByClass.set(className, new Map());
            const key = `${name}|${className}`;
            const map = studentsByClass.get(className);
            if (!map.has(key)) map.set(key, { name, class: className });
        }

        const todayDate = new Date().toISOString().split('T')[0];
        const merged = [];

        const aDate = (obj) => obj?.date || obj?.tanggal || null;

        for (const j of jadwalArr) {
            const className = j.nama_kelas;
            const classStudents = studentsByClass.get(className) || new Map();

            for (const { name } of classStudents.values()) {
                const match = absensiArr.find(
                    (a) =>
                        (a.name || a.nama_siswa) === name &&
                        (a.class || a.nama_kelas) === className &&
                        (a.subject || a.mata_pelajaran) === j.mata_pelajaran &&
                        a.hari === j.hari,
                );

                const record = {
                    name,
                    class: className,
                    subject: j.mata_pelajaran,
                    hari: j.hari,
                    lantai: j.lantai,
                    ruang: j.ruang,
                    date: match ? aDate(match) || todayDate : todayDate,
                    time: match ? match.time || match.waktu || '' : '',
                    status: match ? match.status || 'Belum Absen' : 'Belum Absen',
                    keterangan: match ? match.keterangan || null : null,
                };
                merged.push(record);
            }
        }

        return merged;
    });

    return {
        normalizeHari,
        getTodayHariLower,
        parseTimeToMinutes,
        formatHari,
        processAttendanceStatus,
        getAttendanceStatusClass,
        filteredAbsensiData,
    };
}
