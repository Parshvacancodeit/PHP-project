document.getElementById('registerForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const response = await fetch('register.php', {
        method: 'POST',
        body: formData
    });
    
    const result = await response.text();
    document.getElementById('registerMessage').innerText = result;
});
