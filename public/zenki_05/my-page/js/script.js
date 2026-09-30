const button = document.getElementById('themeButton');

button.addEventListener('click', () => {
    document.body.classList.toggle('dark');

    if (document.body.classList.contains('dark')) {
        button.textContent = 'テーマ変更';
    } else {
        button.textContent = 'テーマ変更';
    }
});
