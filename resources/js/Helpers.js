import { usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

export function numberFormat(number) {
    return number.toLocaleString('en-GB', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

export function moneyFormat(number, currency = null) {
    if (!currency) {
        const page = usePage();
        currency = page.props.app.currency;
    }

    return new Intl.NumberFormat('en-GB', {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(number);
}

export function auth() {
    return usePage().props.auth.user;
}

export const ConfirmModal = Swal.mixin({
    icon: 'info',
    showCancelButton: true,
    confirmButtonText: 'Yes!',
    cancelButtonText: 'No, cancel!',
    confirmButtonColor: '#3085d6',
});

export const Toast = Swal.mixin({
    toast: true,
    position: 'bottom-end',
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    },
});
