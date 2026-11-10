export function showPreloader() {
    document.querySelector('.preloader')?.classList.add('show')
}

export function hidePreloader() {
    document.querySelector('.preloader')?.classList.remove('show')
}

window.showPreloader = showPreloader
window.hidePreloader = hidePreloader