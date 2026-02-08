const API_URL = 'http://localhost:8000';

const app = {
    currentSection: 'movies',
    currentId: null,
    movies: [],
    rooms: [],
    screenings: []
};

document.addEventListener('DOMContentLoaded', () => {
    initNavigation();
    initModal();
    initSearch();
    loadData('movies');
});

function initNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');
    const addBtn = document.getElementById('add-btn');
    
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const section = link.dataset.section;
            switchSection(section);
        });
    });
    
    addBtn.addEventListener('click', () => {
        openModal('create');
    });
}

function switchSection(section) {
    app.currentSection = section;
    
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
        if (link.dataset.section === section) {
            link.classList.add('active');
        }
    });
    
    document.querySelectorAll('.section').forEach(sec => {
        sec.classList.remove('active');
    });
    document.getElementById(`${section}-section`).classList.add('active');
    
    const titles = {
        movies: 'Gestion des Films',
        rooms: 'Gestion des Salles',
        screenings: 'Gestion des Séances'
    };
    document.getElementById('section-title').textContent = titles[section];
    
    loadData(section);
}

async function loadData(section) {
    showLoading(section);
        const response = await fetch(`${API_URL}/${section}`);
        const data = await response.json();
        
        if (section === 'movies') {
            app.movies = data.movies || [];
            renderMovies(app.movies);
        } else if (section === 'rooms') {
            app.rooms = data.rooms || [];
            renderRooms(app.rooms);
        } else if (section === 'screenings') {
            app.screenings = data.screenings || [];
            renderScreenings(app.screenings);
            await loadMoviesForFilter();
        }
}

function showLoading(section) {
    const tbody = document.getElementById(`${section}-tbody`);
    tbody.innerHTML = '<tr><td colspan="7" class="loading">Chargement...</td></tr>';
}

function renderMovies(movies) {
    const tbody = document.getElementById('movies-tbody');
    
    if (movies.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="empty-state">
                    <h3>Aucun film</h3>
                    <p>Commencez par ajouter votre premier film</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = movies.map(movie => `
        <tr>
            <td><strong>${escapeHtml(movie.title)}</strong></td>
            <td>${escapeHtml(movie.director || '-')}</td>
            <td>${escapeHtml(movie.genre || '-')}</td>
            <td>${movie.duration} min</td>
            <td>${movie.release_year}</td>
            <td>
                <button class="btn btn-small btn-edit" onclick="openModal('edit', ${movie.id}, 'movies')">Éditer</button>
                <button class="btn btn-small btn-delete" onclick="deleteItem('movies', ${movie.id})">Supprimer</button>
            </td>
        </tr>
    `).join('');
}

function renderRooms(rooms) {
    const tbody = document.getElementById('rooms-tbody');
    
    if (rooms.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="empty-state">
                    <h3>Aucune salle</h3>
                    <p>Commencez par ajouter votre première salle</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = rooms.map(room => `
        <tr>
            <td><strong>${escapeHtml(room.title)}</strong></td>
            <td>${escapeHtml(room.type || '-')}</td>
            <td>${room.capacity} places</td>
            <td>${escapeHtml(room.description || '-')}</td>
            <td>
                <button class="btn btn-small btn-edit" onclick="openModal('edit', ${room.id}, 'rooms')">Éditer</button>
                <button class="btn btn-small btn-delete" onclick="deleteItem('rooms', ${room.id})">Supprimer</button>
            </td>
        </tr>
    `).join('');
}

function renderScreenings(screenings) {
    const tbody = document.getElementById('screenings-tbody');
    
    if (screenings.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="empty-state">
                    <h3>Aucune séance</h3>
                    <p>Commencez par ajouter votre première séance</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = screenings.map(screening => `
        <tr>
            <td><strong>${escapeHtml(screening.movie_title || '-')}</strong></td>
            <td>${escapeHtml(screening.room_title || '-')}</td>
            <td>${formatDateTime(screening.screening_date)}</td>
            <td>
                <button class="btn btn-small btn-edit" onclick="openModal('edit', ${screening.id}, 'screenings')">Éditer</button>
                <button class="btn btn-small btn-delete" onclick="deleteItem('screenings', ${screening.id})">Supprimer</button>
            </td>
        </tr>
    `).join('');
}

function initModal() {
    const modal = document.getElementById('modal');
    const closeBtn = document.querySelector('.close');
    const cancelBtn = document.getElementById('cancel-btn');
    const form = document.getElementById('modal-form');
    
    closeBtn.onclick = closeModal;
    cancelBtn.onclick = closeModal;
    
    window.onclick = (e) => {
        if (e.target === modal) {
            closeModal();
        }
    };
    
    form.onsubmit = async (e) => {
        e.preventDefault();
        await handleSubmit();
    };
}

async function openModal(mode, id = null, section = null) {
    const modal = document.getElementById('modal');
    const title = document.getElementById('modal-title');
    const formFields = document.getElementById('form-fields');
    
    app.currentId = id;
    const currentSection = section || app.currentSection;
    
    const titles = {
        movies: mode === 'create' ? 'Ajouter un film' : 'Éditer le film',
        rooms: mode === 'create' ? 'Ajouter une salle' : 'Éditer la salle',
        screenings: mode === 'create' ? 'Ajouter une séance' : 'Éditer la séance'
    };
    title.textContent = titles[currentSection];
    
    formFields.innerHTML = await generateFormFields(currentSection, mode, id);
    
    modal.classList.add('show');
}

function closeModal() {
    const modal = document.getElementById('modal');
    modal.classList.remove('show');
    app.currentId = null;
}

async function generateFormFields(section, mode, id) {
    let fields = '';
    let data = {};
    if (mode === 'edit' && id) {
        try {
            const response = await fetch(`${API_URL}/${section}/${id}`);
            const result = await response.json();
            data = result.movie || result.room || result.screening || {};
        } catch (error) {
            showToast('Erreur lors du chargement des données', 'error');
        }
    }
    
    if (section === 'movies') {
        fields = `
            <div class="form-group">
                <label for="title">Titre *</label>
                <input type="text" id="title" name="title" value="${data.title || ''}" required>
            </div>
            <div class="form-group">
                <label for="director">Réalisateur</label>
                <input type="text" id="director" name="director" value="${data.director || ''}">
            </div>
            <div class="form-group">
                <label for="genre">Genre</label>
                <input type="text" id="genre" name="genre" value="${data.genre || ''}">
            </div>
            <div class="form-group">
                <label for="duration">Durée (minutes) *</label>
                <input type="number" id="duration" name="duration" value="${data.duration || ''}" required>
            </div>
            <div class="form-group">
                <label for="release_year">Année de sortie *</label>
                <input type="number" id="release_year" name="release_year" value="${data.release_year || new Date().getFullYear()}" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description">${data.description || ''}</textarea>
            </div>
        `;
    } else if (section === 'rooms') {
        fields = `
            <div class="form-group">
                <label for="title">Nom de la salle *</label>
                <input type="text" id="title" name="title" value="${data.title || ''}" required>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <select id="type" name="type">
                    <option value="">Sélectionner...</option>
                    <option value="Standard" ${data.type === 'Standard' ? 'selected' : ''}>Standard</option>
                    <option value="IMAX" ${data.type === 'IMAX' ? 'selected' : ''}>IMAX</option>
                    <option value="3D" ${data.type === '3D' ? 'selected' : ''}>3D</option>
                    <option value="VIP" ${data.type === 'VIP' ? 'selected' : ''}>VIP</option>
                </select>
            </div>
            <div class="form-group">
                <label for="capacity">Capacité (nombre de places) *</label>
                <input type="number" id="capacity" name="capacity" value="${data.capacity || ''}" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description">${data.description || ''}</textarea>
            </div>
        `;
    } else if (section === 'screenings') {
        if (app.movies.length === 0) {
            const response = await fetch(`${API_URL}/movies`);
            const moviesData = await response.json();
            app.movies = moviesData.movies || [];
        }
        if (app.rooms.length === 0) {
            const response = await fetch(`${API_URL}/rooms`);
            const roomsData = await response.json();
            app.rooms = roomsData.rooms || [];
        }
        
        fields = `
            <div class="form-group">
                <label for="movie_id">Film *</label>
                <select id="movie_id" name="movie_id" required>
                    <option value="">Sélectionner un film...</option>
                    ${app.movies.map(movie => `
                        <option value="${movie.id}" ${data.movie_id == movie.id ? 'selected' : ''}>
                            ${escapeHtml(movie.title)}
                        </option>
                    `).join('')}
                </select>
            </div>
            <div class="form-group">
                <label for="room_id">Salle *</label>
                <select id="room_id" name="room_id" required>
                    <option value="">Sélectionner une salle...</option>
                    ${app.rooms.map(room => `
                        <option value="${room.id}" ${data.room_id == room.id ? 'selected' : ''}>
                            ${escapeHtml(room.title)} (${room.capacity} places)
                        </option>
                    `).join('')}
                </select>
            </div>
            <div class="form-group">
                <label for="screening_date">Date et heure *</label>
                <input type="datetime-local" id="screening_date" name="screening_date" 
                       value="${data.screening_date ? formatDateTimeLocal(data.screening_date) : ''}" required>
            </div>
        `;
    }
    
    return fields;
}
async function handleSubmit() {
    const formData = new FormData(document.getElementById('modal-form'));
    const data = Object.fromEntries(formData);
    const section = app.currentSection;
    
    try {
        let url = `${API_URL}/${section}`;
        let method = 'POST';
        
        if (app.currentId) {
            url += `/${app.currentId}`;
            method = 'PUT';
        }
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (response.ok) {
            showToast(app.currentId ? 'Modification réussie' : 'Création réussie', 'success');
            closeModal();
            loadData(section);
        } else {
            if (result.errors) {
                showToast(result.errors.join(', '), 'error');
            } else {
                showToast(result.error || 'Une erreur est survenue', 'error');
            }
        }
    } catch (error) {
        showToast('Erreur lors de la sauvegarde', 'error');
        console.error(error);
    }
}
async function deleteItem(section, id) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
        return;
    }
    
    try {
        const response = await fetch(`${API_URL}/${section}/${id}`, {
            method: 'DELETE'
        });
        
        const result = await response.json();
        
        if (response.ok) {
            showToast('Suppression réussie', 'success');
            loadData(section);
        } else {
            showToast(result.error || 'Erreur lors de la suppression', 'error');
        }
    } catch (error) {
        showToast('Erreur lors de la suppression', 'error');
        console.error(error);
    }
}
function initSearch() {
    const searchInput = document.getElementById('search-movies');
    let timeout;
    
    searchInput.addEventListener('input', (e) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            const query = e.target.value.trim();
            if (query) {
                searchMovies(query);
            } else {
                loadData('movies');
            }
        }, 300);
    });
    const filterDate = document.getElementById('filter-date');
    const filterMovie = document.getElementById('filter-movie');
    
    filterDate.addEventListener('change', filterScreenings);
    filterMovie.addEventListener('change', filterScreenings);
}

async function searchMovies(query) {
    try {
        const response = await fetch(`${API_URL}/movies?q=${encodeURIComponent(query)}`);
        const data = await response.json();
        app.movies = data.movies || [];
        renderMovies(app.movies);
    } catch (error) {
        showToast('Erreur lors de la recherche', 'error');
    }
}

async function filterScreenings() {
    const date = document.getElementById('filter-date').value;
    const movieId = document.getElementById('filter-movie').value;
    
    let url = `${API_URL}/screenings`;
    const params = [];
    
    if (date) params.push(`date=${date}`);
    if (movieId) params.push(`movie=${movieId}`);
    
    if (params.length > 0) {
        url += '?' + params.join('&');
    }
    
    try {
        const response = await fetch(url);
        const data = await response.json();
        app.screenings = data.screenings || [];
        renderScreenings(app.screenings);
    } catch (error) {
        showToast('Erreur lors du filtrage', 'error');
    }
}

async function loadMoviesForFilter() {
    if (app.movies.length === 0) {
        const response = await fetch(`${API_URL}/movies`);
        const data = await response.json();
        app.movies = data.movies || [];
    }
    
    const select = document.getElementById('filter-movie');
    select.innerHTML = '<option value="">Tous les films</option>' + 
        app.movies.map(movie => `
            <option value="${movie.id}">${escapeHtml(movie.title)}</option>
        `).join('');
}
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast ${type} show`;
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('fr-FR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function formatDateTimeLocal(dateString) {
    const date = new Date(dateString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${year}-${month}-${day}T${hours}:${minutes}`;
}
