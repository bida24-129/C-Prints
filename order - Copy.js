function toggleColorSpecify(value) {
    const colorSpecify = document.getElementById('colorSpecify');
    if (value === 'Specify') {
        colorSpecify.style.display = 'block';
    } else {
        colorSpecify.style.display = 'none';
    }
}

function handleOrder(event) {
    event.preventDefault();
    const form = document.getElementById('OrderForm');
    const formData = new FormData(form);

    // Send order to WhatsApp
    const phone = '26773684840'; // Your WhatsApp number
    const message = `Order Details:\n\nName: ${formData.get('name')}\nEmail: ${formData.get('email')}\nPhone: ${formData.get('phone')}\nAddress: ${formData.get('address')}\nSize: ${formData.get('size')}\nQuantity: ${formData.get('quantity')}\nDescription: ${formData.get('description')}\nColor: ${formData.get('color')}\nSpecify Color: ${formData.get('color_specify')}`;
    const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');

    // Save order to database
    fetch('save-order.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('responseMessage').innerText = data;
    })
    .catch(error => {
        console.error('Error:', error);
    });
}