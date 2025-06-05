const searchInput = document.getElementById('search-input');
const baseRoute = document.querySelector('meta[name="base-route"]').content;

if (searchInput) {
    searchInput.addEventListener('input', function () {
        if (this.value.trim() === '') {
            window.location.href = baseRoute;
        }
    });
}

const clearBtn = document.getElementById('clear-search');
if (clearBtn) {
    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        window.location.href = baseRoute;
    });
}

setTimeout(() => {
    const message = document.getElementById('success-message');
    if (message) message.style.display = 'none';
}, 3000);

// Filtros avanzados
document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('toggle-filters');
    const filtersDiv = document.getElementById('advancedFilters');
    const filterIcon = document.getElementById('filter-icon');

    const clearFiltersBtn = document.getElementById('clear-filters-btn');

    function showFilters() {
        filtersDiv.classList.remove('hidden');
        toggleBtn.classList.remove('bg-[#1D4ED8]', 'text-white');
        toggleBtn.classList.add('bg-white', 'text-[#1D4ED8]');
        filterIcon.classList.remove('bi-funnel');
        filterIcon.classList.add('bi-funnel-fill');
    }

    function hideFilters() {
        filtersDiv.classList.add('hidden');
        toggleBtn.classList.add('bg-[#1D4ED8]', 'text-white');
        toggleBtn.classList.remove('bg-white', 'text-[#1D4ED8]');
        filterIcon.classList.remove('bi-funnel-fill');
        filterIcon.classList.add('bi-funnel');
    }

    function toggleFiltersVisible() {
        const isVisible = !filtersDiv.classList.contains('hidden');
        if (isVisible) {
            hideFilters();
        } else {
            showFilters();
        }
    }

    if (toggleBtn && filtersDiv) {
        toggleBtn.addEventListener('click', toggleFiltersVisible);

        // Mostrar si hay filtros activos (al cargar)
        const url = new URL(window.location);
        if (url.searchParams.has('date_from') || url.searchParams.has('date_to')) {
            showFilters();
        }
    }

    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function (e) {
            e.preventDefault();
            window.location.href = baseRoute;
        });
    }
});
