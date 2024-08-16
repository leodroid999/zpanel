<template>
    <div>
      <!-- Breadcrumbs -->
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Z-panel</a></li>
        <li class="breadcrumb-item active">Memo</li>
      </ul>
  
      <!-- Page Header -->
      <h1 class="">
        Panel Deploy
      </h1>
  
      <!-- Alert -->
      <div class="mt-5">
        <div class="alert alert-warning" role="alert">
          Panels are deployed instantly but you must still manually host them with your own domain and VPS!
        </div>
  
        <!-- Select Panel Section -->
        <div class="mb-4">
          <h3>Available panels</h3>
          <p>Decide how much resources you need</p>
        </div>
  
        <!-- Panel Cards -->
      <div class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col">
  	<div class="card h-100 selected">
            <div class="card-body text-center">
              <div class="card-arrow green">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
              </div>
              <h5 class="card-title">KBC</h5>
              <div class="logo-container">
                <img src="https://upload.wikimedia.org/wikipedia/en/d/d8/KBC_Bank_logo.svg" alt="Regular Logo" class="card-logo">
              </div>
       
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card h-100">
            <div class="card-body text-center">
              <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
              </div>
              <h5 class="card-title">GoDaddy</h5>
              <img src="https://wordpress.org/five-for-the-future/files/2019/11/GD_LOCKUP_CMYK_BLACK_NO_MARK-1.png" alt="Business Logo" class="card-logo">
      
            </div>
          </div>
        </div>

 	<div class="col">
          <div class="card h-100">
            <div class="card-body text-center">
              <div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
              </div>
              <h5 class="card-title">View more panels..</h5>
            </div>
          </div>
        </div>
        
        
        
      </div>

      <!-- Select Number of Days Section -->
        <div class="my-4">
          <h3>Select Number of Days</h3>
          <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col">
              <div class="card text-center">
                <div class="card-body">
<div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
              </div>
                  <h5 class="card-title">2 Days</h5>
                  <p class="card-text">50€</p>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card text-center">
                <div class="card-body">
<div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
              </div>
                  <h5 class="card-title">7 Days</h5>
                  <p class="card-text">100€</p>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card text-center">
                <div class="card-body">
<div class="card-arrow">
                <div class="card-arrow-top-left"></div>
                <div class="card-arrow-top-right"></div>
                <div class="card-arrow-bottom-left"></div>
                <div class="card-arrow-bottom-right"></div>
              </div>
                  <h5 class="card-title">30 Days</h5>
                  <p class="card-text">250€</p>
                </div>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Panel Password Input -->
        <div class="my-4">
          <h3>Panel password</h3>
          <input type="password" placeholder="F****" class="form-control m-4 mx-auto">
        </div>
 <a href="#" class="btn btn-outline-theme btn-lg active float-right">Deploy for 70$</a>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'DeployPage',
  };
  </script>
  
  <style scoped>
.logo-container {
  padding: 10px; /* Padding around the logo */
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100px; /* Set a fixed height for the container */
}

.card-logo {
  max-height: 80px; /* Adjust the max height as needed */
  max-width: 100%;
}
  </style>
  