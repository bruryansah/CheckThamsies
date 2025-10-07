import { ref } from 'vue';

export function useNotification() {
    const toastMessage = ref('');
    const toastType = ref('success');
    const showToast = ref(false);

    const showNotification = (msg, type = 'success') => {
        toastMessage.value = msg;
        toastType.value = type;
        showToast.value = true;
        setTimeout(() => (showToast.value = false), 3000);
    };

    return {
        toastMessage,
        toastType,
        showToast,
        showNotification,
    };
}
