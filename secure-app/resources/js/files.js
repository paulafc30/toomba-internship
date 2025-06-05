document.addEventListener('DOMContentLoaded', function () {
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('fileInput');
    const fileNameInput = document.getElementById('fileName');
    const uploadButton = document.getElementById('uploadButton');
    const successMessage = document.getElementById('success-message');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const folderId = document.querySelector('meta[name="folder-id"]')?.content || '';
    const baseFolderRoute = document.querySelector('meta[name="base-folder-route"]')?.content || '';
    const searchInput = document.getElementById('search-input');
    const urlFiles = folderId && baseFolderRoute ? `${baseFolderRoute}/${folderId}/files` : null;
    const clearBtn = document.getElementById('clear-search');

    dropzone?.addEventListener('click', () => fileInput.click());

    fileInput?.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            fileNameInput.value = fileInput.files[0].name;
            uploadButton.disabled = false;
        } else {
            fileNameInput.value = '';
            uploadButton.disabled = true;
        }
    });

    dropzone?.addEventListener('dragover', e => {
        e.preventDefault();
        dropzone.classList.add('bg-gray-100');
    });

    dropzone?.addEventListener('dragleave', e => {
        e.preventDefault();
        dropzone.classList.remove('bg-gray-100');
    });

    dropzone?.addEventListener('drop', e => {
        e.preventDefault();
        dropzone.classList.remove('bg-gray-100');
        const file = e.dataTransfer.files[0];
        if (file) {
            fileInput.files = e.dataTransfer.files;
            fileNameInput.value = file.name;
            uploadButton.disabled = false;
        }
    });

    uploadButton?.addEventListener('click', () => {
        if (fileInput.files.length === 0) {
            alert("Por favor, selecciona un archivo primero.");
            return;
        }
        uploadFile(fileInput.files[0]);
        uploadButton.disabled = true;
    });

    function uploadFile(file) {
        if (!folderId || !baseFolderRoute) {
            alert("No se pudo determinar la carpeta para subir el archivo.");
            uploadButton.disabled = false;
            return;
        }

        const formData = new FormData();
        formData.append('file', file);
        const uploadUrl = `${baseFolderRoute}/${folderId}/files/upload`;

        fetch(uploadUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || "Error al subir el archivo.");
                uploadButton.disabled = false;
            }
        })
        .catch(() => {
            alert("Error al subir el archivo.");
            uploadButton.disabled = false;
        });
    }

    if (searchInput && urlFiles) {
        searchInput.addEventListener('input', function () {
            if (this.value.trim() === '') {
                window.location.href = urlFiles;
            }
        });
    }

    clearBtn?.addEventListener('click', () => {
        if (searchInput && urlFiles) {
            searchInput.value = '';
            window.location.href = urlFiles;
        }
    });

    setTimeout(() => {
        if (successMessage) successMessage.style.display = 'none';
    }, 3000);

    // Filtros avanzados
    const toggleBtn = document.getElementById('filter-button');
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
        isVisible ? hideFilters() : showFilters();
    }

    if (toggleBtn && filtersDiv) {
        toggleBtn.addEventListener('click', toggleFiltersVisible);

        const url = new URL(window.location);
        const activeFilterParams = ['date_from', 'date_to', 'file_type', 'file_name'];
        const hasActiveFilters = activeFilterParams.some(param => url.searchParams.has(param));
        if (hasActiveFilters) showFilters();
    }

    clearFiltersBtn?.addEventListener('click', function (e) {
        e.preventDefault();
        window.location.href = urlFiles;
    });
});
