function sendToWhatsApp() {
    
    let number = "26773684840"; 

    let name = document.getElementById('name').value;
    let email = document.getElementById('email').value;
    let message = document.getElementById('message').value;

    let url = "https://wa.me/" + number + "?text=" +
        encodeURIComponent(
            "Name: " + name + "\n" +
            "Email: " + email + "\n" +
            "Message: " + message + " \n"
            
        );

    window.open(url, '_blank');

    
    setTimeout(() => {
        window.location.href = 'thanks.html';
    }, 1000);
}
