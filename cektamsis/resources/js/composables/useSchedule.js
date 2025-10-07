import { ref, computed } from 'vue';

export function useSchedule(jadwalData) {
    const selectedHari = ref('');
    const selectedLantai = ref('');
    const selectedRuang = ref('');

    const availableLantai = computed(() => {
        const jadwalArray = Array.isArray(jadwalData.value) ? jadwalData.value : [];
        return [...new Set(jadwalArray.map((item) => item.lantai))].sort();
    });

    const availableRuang = computed(() => {
        const jadwalArray = Array.isArray(jadwalData.value) ? jadwalData.value : [];
        return [...new Set(jadwalArray.map((item) => item.ruang))].sort();
    });

    const filteredSchedule = computed(() => {
        let filtered = jadwalData.value ?? [];

        if (selectedHari.value) {
            filtered = filtered.filter((item) => item.hari === selectedHari.value);
        }
        if (selectedLantai.value) {
            filtered = filtered.filter((item) => item.lantai === selectedLantai.value);
        }
        if (selectedRuang.value) {
            filtered = filtered.filter((item) => item.ruang === selectedRuang.value);
        }

        return filtered.sort((a, b) => {
            const dayOrder = { senin: 1, selasa: 2, rabu: 3, kamis: 4, jumat: 5 };
            const dayComparison = dayOrder[a.hari] - dayOrder[b.hari];

            if (dayComparison !== 0) {
                return dayComparison;
            }

            const timeA = a.jam_mulai.split(':').map(Number);
            const timeB = b.jam_mulai.split(':').map(Number);
            const timeAMinutes = timeA[0] * 60 + timeA[1];
            const timeBMinutes = timeB[0] * 60 + timeB[1];

            return timeAMinutes - timeBMinutes;
        });
    });

    return {
        selectedHari,
        selectedLantai,
        selectedRuang,
        availableLantai,
        availableRuang,
        filteredSchedule,
    };
}
