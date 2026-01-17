import Swal from 'sweetalert2';
import { Chart, registerables } from 'chart.js';
import ApexCharts from 'apexcharts';

Chart.register(...registerables);

window.Swal = Swal;
window.ChartJS = Chart;
window.ApexCharts = ApexCharts;

// Register Alpine stores & directives during Alpine's init phase
document.addEventListener('alpine:init', () => {
    const Alpine = window.Alpine;

    Alpine.store('modal', {
        open: false,
        loading: false,
        title: '',
        body: '',
        action: null,
        method: 'post',
        actionUrl: null,
        size: 'md',
        toggle() {
            this.open = !this.open;
        },
        show({ title, body, actionUrl, method = 'post', size = 'md' }) {
            this.title = title;
            this.body = body;
            this.actionUrl = actionUrl;
            this.method = method;
            this.size = size;
            this.open = true;
        },
        close() {
            this.reset();
        },
        reset() {
            this.open = false;
            this.loading = false;
            this.title = '';
            this.body = '';
            this.actionUrl = null;
            this.method = 'post';
            this.size = 'md';
        },
    });

    Alpine.directive('ajax', (el, { modifiers }, { expression, evaluate }) => {
        const options = expression ? evaluate(expression) : {};

        el.addEventListener('submit', async (event) => {
            event.preventDefault();

            const formData = new FormData(el);
            const method = (options?.method || el.method || 'POST').toUpperCase();
            const url = options?.url || el.action;
            const showLoading = !modifiers.includes('silent');

            try {
                if (showLoading) {
                    Alpine.store('modal').loading = true;
                }

                const response = await window.axios.request({
                    url,
                    method,
                    data: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (options?.onSuccess) {
                    options.onSuccess(response);
                }

                if (options?.successMessage) {
                    Swal.fire({
                        icon: 'success',
                        title: options.successMessage.title || 'Success',
                        text: options.successMessage.text || 'Operation completed successfully.',
                        timer: 2000,
                        showConfirmButton: false,
                    });
                }

                if (options?.reset !== false && typeof el.reset === 'function') {
                    el.reset();
                }

                el.dispatchEvent(new CustomEvent('ajax:success', { detail: response }));
            } catch (error) {
                const message =
                    error.response?.data?.message ||
                    error.message ||
                    'Unexpected error occurred.';

                if (options?.onError) {
                    options.onError(error);
                }

                Swal.fire({
                    icon: 'error',
                    title: options?.errorMessage?.title || 'Error',
                    text: options?.errorMessage?.text || message,
                });

                el.dispatchEvent(new CustomEvent('ajax:error', { detail: error }));
            } finally {
                if (showLoading) {
                    Alpine.store('modal').loading = false;
                }
            }
        });
    });
});

window.Admin = {
    async showModal({ title = 'Modal', url = null, method = 'get', size = 'md', body = null }) {
        const modalStore = window.Alpine.store('modal');

        if (body !== null) {
            modalStore.show({ title, body, actionUrl: null, method, size });
            return;
        }

        if (!url) {
            throw new Error('Either `url` or `body` is required to load modal content.');
        }

        modalStore.show({ title, body: '<div class="py-6 text-center text-slate-500">Loading...</div>', actionUrl: url, method, size });

        try {
            const response = await window.axios.request({
                url,
                method,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });

            modalStore.body = response.data.html ?? response.data;
        } catch (error) {
            modalStore.body = `<div class="rounded-lg bg-rose-50 p-4 text-sm text-rose-600">Failed to load content. ${error.message}</div>`;
        }
    },

    async confirmAction({ title = 'Are you sure?', text = '', confirmButtonText = 'Yes', cancelButtonText = 'Cancel', icon = 'warning' }) {
        const result = await Swal.fire({
            title,
            text,
            icon,
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText,
            cancelButtonText,
        });

        return result.isConfirmed;
    },
};

