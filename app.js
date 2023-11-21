const API_KEY = `4b8fc946743b69cb7f5c80a912dce351`
const image_path = `https://image.tmdb.org/t/p/w1280`

const input = document.querySelector('.search input')
const btn = document.querySelector('.search button')
const main_grid_title = document.querySelector('.favorites h1')
const main_grid = document.querySelector('.favorites .movies-grid')
const trending_el = document.querySelector('.trending .movies-grid')
const comedy_el = document.querySelector('.comedy .movies-grid')
const drama_el = document.querySelector('.drama .movies-grid')
const science_fiction_el = document.querySelector('.science_fiction .movies-grid')
const action_el = document.querySelector('.action .movies-grid')
const popup_container = document.querySelector('.popup-container')

function add_click_effect_to_card (cards) {
    
    cards.forEach(card => {
        card.addEventListener('click', () => show_popup(card))

    })
    
}

// SEARCH MOVIES
async function get_movie_by_search (search_term) {
    const resp = await fetch(`https://api.themoviedb.org/3/search/movie?api_key=${API_KEY}&query=${search_term}`)
    const respData = await resp.json()
    return respData.results
}

btn.addEventListener('click', add_searched_movies_to_dom)

async function add_searched_movies_to_dom () {
    const data = await get_movie_by_search(input.value)

    main_grid_title.innerText = `Search Results...`
    main_grid.innerHTML = data.map(e => {
        return `
            <div class="card" data-id="${e.id}">
                <div class="img">
                    <img src="${image_path + e.poster_path}">
                </div>
                <div class="info">
                    <h2>${e.title}</h2>
                    <div class="single-info">
                        <span>Rate: </span>
                        <span>${e.vote_average} </span>

                    </div>
                    <div class="single-info">
                        <span>Release Date: </span>
                        <span>${e.release_date}</span>
                    </div>
                    <div class="rate-movie">
                        <span>Rate this movie: </span>                        
                    </div>
                </div>
            </div>
        `
    }).join('')

    const cards = document.querySelectorAll('.card')
    add_click_effect_to_card(cards)
}

// POPUP
async function get_movie_by_id (id) {
    const resp = await fetch(`https://api.themoviedb.org/3/movie/${id}?api_key=${API_KEY}`)
    const respData = await resp.json()
    return respData
}
async function get_movie_trailer(id) {
    const resp = await fetch(`https://api.themoviedb.org/3/movie/${id}/videos?api_key=${API_KEY}`)
    const respData = await resp.json()

    console.log(respData); 
    console.log(respData.results); 

    if (respData.results && respData.results.length > 0) {
        return respData.results[0].key;
    } else {
        console.error('Nije pronađen ključ za trailer u odgovoru API-ja');
        return null;  
    }
}

async function get_comments_for_movie(movieId) {
    const response = await fetch(`get_comments.php?movieId=${movieId}`);
    const data = await response.json();
    return data.comments;
}

async function fetch_comments_and_ratings(movieId) {
    const comments = await get_comments_for_movie(movieId);
    const commentsSection = popup_container.querySelector('.comments-section');
    commentsSection.innerHTML = `
        <h2>Comments & Ratings</h2>
        ${comments.map(comment => `
            <div class="comment">
                <p><strong>Comment:</strong> ${comment.comment}</p>
                <div class="rating">Rating: ${comment.rating}</div>
            </div>
        `).join('')}
        <textarea placeholder="Write your comment"></textarea>
        <input type="number" min="1" max="10" step="0.1" placeholder="Enter your rating" />
        <button class="submit-btn">Submit</button>
    `;
}

async function show_popup(card) {
    popup_container.classList.add('show-popup');

    const movie_id = card.getAttribute('data-id');
    const movie = await get_movie_by_id(movie_id);
    const movie_trailer = await get_movie_trailer(movie_id);

    popup_container.style.background = `linear-gradient(rgba(0, 0, 0, .8), rgba(0, 0, 0, 1)), url(${image_path + movie.poster_path})`;

    popup_container.innerHTML = `
        <span class="x-icon">&#10006;</span>
        <div class="content">
            <div class="left">
                <div class="poster-img">
                    <img src="${image_path + movie.poster_path}" alt="">
                </div>
                <div class="single-info">
                    <span>Add to favorites:</span>
                    <span class="heart-icon">&#9829;</span>
                </div>
            </div>
            <div class="right">
                <h1>${movie.title}</h1>
                <h3>${movie.tagline}</h3>
                <div class="single-info-container">
                    <div class="single-info">
                        <span>Language:</span>
                        <span>${movie.spoken_languages[0].name}</span>
                    </div>
                    <div class="single-info">
                        <span>Length:</span>
                        <span>${movie.runtime} minutes</span>
                    </div>
                    <div class="single-info">
                        <span>Rate:</span>
                        <span>${movie.vote_average} /10</span>
                    </div>
                    <div class="single-info">
                        <span>Budget:</span>
                        <span>$ ${movie.budget}</span>
                    </div>
                    <div class="single-info">
                        <span>Release Date:</span>
                        <span>${movie.release_date}</span>
                    </div>
                </div>                
                <div class="overview">
                    <h2>Overview</h2>
                    <p>${movie.overview}</p>
                </div>
                <div class="trailer">
                    <h2>Trailer</h2>
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/${movie_trailer}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="comments-section">                   
                </div>
            </div>
        </div>
    `;

    const x_icon = document.querySelector('.x-icon');
    x_icon.addEventListener('click', () => {
        popup_container.classList.remove('show-popup');      
    });

    const heart_icon = popup_container.querySelector('.heart-icon');

    //const movie_ids =  get_favorites_for_movie(); 

    const isFavorite = await isMovieFavorite(movie_id);    

    if (isFavorite) {
        heart_icon.classList.add('change-color');
    } else {
        heart_icon.classList.remove('change-color'); 
    }

    heart_icon.addEventListener('click', async () => {
        if (heart_icon.classList.contains('change-color')) {         
            const responsee = await fetch('delete_favorites.php', {
              
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    movieId: movie_id,                   
                }),
            });
            heart_icon.classList.remove('change-color');
        
        } else {         
            const responsee = await fetch('add_favorites.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    movieId: movie_id,                   
                }),
            });
    
            heart_icon.classList.add('change-color');
           
        }       
        
        fetch_favorite_movies();      
    });

    
    await fetch_comments_and_ratings(movie_id);  

    const submitBtn = popup_container.querySelector('.submit-btn');
    submitBtn.addEventListener('click', async () => {
        const commentInput = popup_container.querySelector('textarea');
        const ratingInput = popup_container.querySelector('input');
    
        const comment = commentInput.value.trim();
        const rating = parseFloat(ratingInput.value);
    
        const response = await fetch('post_comments.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                movieId: movie_id,
                comment: comment,
                rating: rating,
            }),
        });
    
        const responseData = await response.json();
        if (responseData.success) {
            console.log('Comment and rating saved successfully.');    
            await fetch_comments_and_ratings(movie_id);
        } else {
            console.error('Error saving comment and rating:', responseData.error);
        }
        commentInput.value = '';
        ratingInput.value = '';
    });
    
    
}
async function isMovieFavorite(movie_id) {    
    const movie_ids = await get_favorites_for_movie();

    for (let i = 0; i < movie_ids.length; i++) {
        if (String(movie_ids[i].movie_id) === String(movie_id)) {           
            return true;
        }
    }    
    return false;
}


async function get_favorites_for_movie() {
    try {
        const response = await fetch(`get_favorites.php`);
        const data = await response.json();
        return data.favorites || []; 
    } catch (error) {
        console.error('Error fetching favorites:', error);
        return []; 
    }
}

// Favorite Movies
fetch_favorite_movies()
async function fetch_favorite_movies () {
    main_grid.innerHTML = '';  
   
    const movies_LS = await get_favorites_for_movie()
    const movies = []
    for (let i = 0; i < movies_LS.length; i++) { 
        const movie_id = movies_LS[i].movie_id; 
        let movie = await get_movie_by_id(movie_id)
        add_favorites_to_dom_from_LS(movie)
        movies.push(movie)
    }
}


function add_favorites_to_dom_from_LS (movie_data) {
    main_grid.innerHTML += `
        <div class="card" data-id="${movie_data.id}">
            <div class="img">
                <img src="${image_path + movie_data.poster_path}">
            </div>
            <div class="info">
                <h2>${movie_data.title}</h2>
                <div class="single-info">
                    <span>Rate: </span>
                    <span>${movie_data.vote_average} / 10</span>
                </div>
                <div class="single-info">
                    <span>Release Date: </span>
                    <span>${movie_data.release_date}</span>
                </div>
            </div>
        </div>
    `;
    const cards = document.querySelectorAll('.card')
    add_click_effect_to_card(cards)
}



// Trending Movies
get_trending_movies()
async function get_trending_movies () {
    const resp = await fetch(`https://api.themoviedb.org/3/trending/all/day?api_key=${API_KEY}`)
    const respData = await resp.json()
    return respData.results
}


add_to_dom_trending()
async function add_to_dom_trending () {

    const data = await get_trending_movies()
    console.log(data);

    trending_el.innerHTML = data.slice(0, 10).map(e => {
        return `
            <div class="card" data-id="${e.id}">
                <div class="img">
                    <img src="${image_path + e.poster_path}">
                </div>
                <div class="info">
                    <h2>${e.title}</h2>
                    <div class="single-info">
                        <span>Rate: </span>
                        <span>${e.vote_average} /10 </span>
                    </div>
                    <div class="single-info">
                        <span>Release Date: </span>
                        <span>${e.release_date}</span>
                    </div>
                </div>
            </div>
        `
    }).join('')

    const cards = document.querySelectorAll('.card')
    add_click_effect_to_card(cards)
}

// Comedy Movies
get_comedy()
async function get_comedy () {
    const resp = await fetch(`https://api.themoviedb.org/3/discover/movie?api_key=${API_KEY}&with_genres=35&sort_by=popularity.desc`)
    const respData = await resp.json()
    return respData.results
}

add_to_dom_comedy()
async function add_to_dom_comedy () {

    const data = await get_comedy()
    console.log(data);

    comedy_el.innerHTML = data.slice(0, 10).map(e => {
        return `
            <div class="card" data-id="${e.id}">
                <div class="img">
                    <img src="${image_path + e.poster_path}">
                </div>
                <div class="info">
                    <h2>${e.title}</h2>
                    <div class="single-info">
                        <span>Rate: </span>
                        <span>${e.vote_average} /10 </span>
                    </div>
                    <div class="single-info">
                        <span>Release Date: </span>
                        <span>${e.release_date}</span>
                    </div>
                </div>
            </div>
        `
    }).join('')

    const cards = document.querySelectorAll('.card')
    add_click_effect_to_card(cards)
}


// Drama Movies
get_drama()
async function get_drama () {
    const resp = await fetch(`https://api.themoviedb.org/3/discover/movie?api_key=${API_KEY}&with_genres=18&sort_by=popularity.desc`)
    const respData = await resp.json()
    return respData.results
}

add_to_dom_drama()
async function add_to_dom_drama () {

    const data = await get_drama()
    console.log(data);

    drama_el.innerHTML = data.slice(0, 10).map(e => {
        return `
            <div class="card" data-id="${e.id}">
                <div class="img">
                    <img src="${image_path + e.poster_path}">
                </div>
                <div class="info">
                    <h2>${e.title}</h2>
                    <div class="single-info">
                        <span>Rate: </span>
                        <span>${e.vote_average} /10 </span>
                    </div>
                    <div class="single-info">
                        <span>Release Date: </span>
                        <span>${e.release_date}</span>
                    </div>
                </div>
            </div>
        `
    }).join('')

    const cards = document.querySelectorAll('.card')
    add_click_effect_to_card(cards)
}

// Action Movies
get_action()
async function get_action () {
    const resp = await fetch(`https://api.themoviedb.org/3/discover/movie?api_key=${API_KEY}&with_genres=28&sort_by=popularity.desc`)
    const respData = await resp.json()
    return respData.results
}

add_to_dom_action()
async function add_to_dom_action () {

    const data = await get_action()
    console.log(data);

    action_el.innerHTML = data.slice(0, 10).map(e => {
        return `
            <div class="card" data-id="${e.id}">
                <div class="img">
                    <img src="${image_path + e.poster_path}">
                </div>
                <div class="info">
                    <h2>${e.title}</h2>
                    <div class="single-info">
                        <span>Rate: </span>
                        <span>${e.vote_average} /10 </span>
                    </div>
                    <div class="single-info">
                        <span>Release Date: </span>
                        <span>${e.release_date}</span>
                    </div>
                </div>
            </div>
        `
    }).join('')

    const cards = document.querySelectorAll('.card')
    add_click_effect_to_card(cards)
}

// Science Fiction
get_science_fiction()
async function get_science_fiction () {
    const resp = await fetch(`https://api.themoviedb.org/3/discover/movie?api_key=${API_KEY}&with_genres=878&sort_by=popularity.desc`)
    const respData = await resp.json()
    return respData.results
}

add_to_dom_science_fiction()
async function add_to_dom_science_fiction() {

    const data = await get_science_fiction()
    console.log(data);

    science_fiction_el.innerHTML = data.slice(0, 10).map(e => {
        return `
            <div class="card" data-id="${e.id}">
                <div class="img">
                    <img src="${image_path + e.poster_path}">
                </div>
                <div class="info">
                    <h2>${e.title}</h2>
                    <div class="single-info">
                        <span>Rate: </span>
                        <span>${e.vote_average} /10 </span>
                    </div>
                    <div class="single-info">
                        <span>Release Date: </span>
                        <span>${e.release_date}</span>
                    </div>
                </div>
            </div>
        `
    }).join('')

    const cards = document.querySelectorAll('.card')
    add_click_effect_to_card(cards)
}


