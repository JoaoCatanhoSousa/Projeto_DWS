<!-- 
 *Importante information 
 ! Deprecated method, do not use
 TODO: refactor this code
 ? should this method be used?
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Define a codificação de caracteres -->
    <meta charset="UTF-8">
    <!-- Define a viewport para responsividade -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Define o título da página -->
    <title>About Us</title>
    <!-- Link para o Arquivo CSS -->
    <link rel="stylesheet" href="Projeto_DWS/Public/Imagens/dashboard-3510327_640.jpg">
    <!-- Link para o Bootstrap CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Link para o CSS personalizado -->
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/abouts/about-2/assets/css/about-2.css">
</head>
<!-- Inclui o cabeçalho -->
<?php include(__DIR__ . '/Partials/header.php'); ?>
<body>
<section class="py-3 py-md-5">
    <div class="container">
      <div class="row gy-3 gy-md-4 gy-lg-0 align-items-lg-center">
        <div class="col-12 col-lg-6">
          <!--* Precisamos melhorar esta imagem -->
          <img class="img-fluid rounded" loading="lazy" src="/Imagens/dashboard-3510327_640.jpg" alt="Hotel Image">
        </div>
        <div class="col-12 col-lg-6">
          <div class="row justify-content-xl-center">
            <div class="col-12 col-xl-10">
              <!-- Título da seção -->
              <h2 class="mb-3" id="mb-3">Why Choose Us?</h2>
              <!-- Descrição da seção -->
              <p class="lead fs-4 mb-3 mb-xl-5">With years of experience and deep industry knowledge, we have a proven track record of success and are constantly pushing ourselves to stay ahead of the curve.</p>
              <div class="d-flex align-items-center mb-3">
                <div class="me-3 text-primary">
                  <!-- Ícone de verificação -->
                  <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#bfb3f2" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                  </svg>
                </div>
                <div>
                  <!-- Texto de destaque -->
                  <p class="fs-5 m-0">Our evolution procedure is super intelligent.</p>
                </div>
              </div>
              <div class="d-flex align-items-center mb-3">
                <div class="me-3 text-primary">
                  <!-- Ícone de verificação -->
                  <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#bfb3f2" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                  </svg>
                </div>
                <div>
                  <!-- Texto de destaque -->
                  <p class="fs-5 m-0">We deliver services beyond expectations.</p>
                </div>
              </div>
              <div class="d-flex align-items-center mb-4 mb-xl-5">
                <div class="me-3 text-primary">
                  <!-- Ícone de verificação -->
                  <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#bfb3f2" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                  </svg>
                </div>
                <div>
                  <!-- Texto de destaque -->
                  <p class="fs-5 m-0">Let's hire us to reach your objectives.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>
<!-- Inclui o rodapé -->
<?php include(__DIR__ . '/Partials/footer.php'); ?>
</html>