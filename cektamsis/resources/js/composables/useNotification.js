import { ref } from 'vue';

export function useNotification() {
    const toastMessage = ref('');
    const toastType = ref('success');
    const showToast = ref(false);
    let toastTimeout = null;

    const showNotification = (msg, type = 'success') => {
        // Clear timeout sebelumnya jika ada
        if (toastTimeout) {
            clearTimeout(toastTimeout);
        }

        toastMessage.value = msg;
        toastType.value = type;
        showToast.value = true;
        
        // Set timeout baru
        toastTimeout = setTimeout(() => {
            showToast.value = false;
        }, 10000); // 10 detik
    };

    const hideToast = () => {
        if (toastTimeout) {
            clearTimeout(toastTimeout);
        }
        showToast.value = false;
    };

    return {
        toastMessage,
        toastType,
        showToast,
        showNotification,
        hideToast,
    };
}