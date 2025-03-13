document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const inputs = document.querySelectorAll("input[required]");
    
    // Adiciona evento para verificar campos vazios ao tentar enviar o formulário
    form.addEventListener("submit", function (event) {
        let valido = true;
        
        inputs.forEach(input => {
            if (input.value.trim() === "") {
                input.parentElement.style.borderColor = "red"; // Destaca campo obrigatório não preenchido
                valido = false;
            } else {
                input.parentElement.style.borderColor = "#ccc";
            }
        });
        
        if (!valido) {
            event.preventDefault(); // Impede o envio do formulário se houver campos vazios
            alert("Por favor, preencha todos os campos obrigatórios.");
        }
    });
    
    // Exibir senha ao clicar no ícone de olho
    document.querySelectorAll(".fa-eye").forEach(icon => {
        icon.addEventListener("click", function () {
            const input = this.previousElementSibling;
            if (input.type === "password") {
                input.type = "text";
                this.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                input.type = "password";
                this.classList.replace("fa-eye-slash", "fa-eye");
            }
        });
    });
});
