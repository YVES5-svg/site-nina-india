
  document.getElementById('signupForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Empêche le formulaire de se soumettre automatiquement
  
      // Récupère les valeurs des champs du formulaire
      const nom = document.getElementById('nom').value;
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      const rememberMe = document.querySelector('input[type="checkbox"]').checked;
  
      // Vérifie si tous les champs sont remplis
      if (nom === '' || email === '' || password === '' || confirmPassword === '') {
          alert('Tous les champs doivent être remplis');
          return;
      }
  
      // Vérifie si les mots de passe sont identiques
      if (password !== confirmPassword) {
          alert('Les mots de passe ne correspondent pas');
          return;
      }
      // Si tout est bon, soumettre le formulaire
      this.submit();
  });
  