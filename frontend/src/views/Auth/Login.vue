<template>
  <div class="login-page">

    <!-- LEFT SIDE -->
    <section class="login-left">

      <!-- Motif -->
      <div class="pattern pattern-top"></div>
      <div class="pattern pattern-bottom"></div>

      <!-- Logo -->
      <div class="brand">
        <div class="brand-logo">
          <GraduationCap :size="30" />
        </div>

        <div>
          <strong>SIKAP</strong>
          <span>Assalaam</span>
        </div>
      </div>

      <!-- Welcome -->
      <div class="welcome-content">
        <span class="welcome-small">
          Selamat Datang di
        </span>

        <h1>
          SIKAP Assalaam
        </h1>

        <div class="yellow-line"></div>

        <p>
          Sistem Informasi Kedisiplinan dan Prestasi
          untuk mendukung terciptanya siswa yang
          berkarakter dan berprestasi.
        </p>
      </div>

      <!-- Decorative waves -->
      <div class="wave wave-one"></div>
      <div class="wave wave-two"></div>

    </section>


    <!-- RIGHT SIDE -->
    <section class="login-right">

      <div class="login-card">

        <div class="login-header">
          <span>
            Welcome to <strong>SIKAP</strong>
          </span>

          <h2>Sign in</h2>
        </div>


        <form @submit.prevent="handleLogin">

          <!-- USERNAME -->
          <div class="form-group">

            <label for="username">
              Username
            </label>

            <div class="input-box">

              <User :size="19" />

              <input
                id="username"
                v-model="username"
                type="text"
                placeholder="Masukkan username"
                autocomplete="username"
              />

            </div>

          </div>


          <!-- PASSWORD -->
          <div class="form-group">

            <label for="password">
              Password
            </label>

            <div class="input-box">

              <LockKeyhole :size="19" />

              <input
                id="password"
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Masukkan password"
                autocomplete="current-password"
              />

              <button
                type="button"
                class="password-button"
                @click="showPassword = !showPassword"
              >
                <EyeOff
                  v-if="showPassword"
                  :size="19"
                />

                <Eye
                  v-else
                  :size="19"
                />
              </button>

            </div>

          </div>


          <!-- FORGOT PASSWORD -->
          <div class="forgot-password">
            <a href="#" @click.prevent>
              Lupa password?
            </a>
          </div>


          <!-- ERROR -->
          <div
            v-if="errorMessage"
            class="error-message"
          >
            <CircleAlert :size="17" />
            <span>{{ errorMessage }}</span>
          </div>


          <!-- LOGIN BUTTON -->
          <button
            type="submit"
            class="login-button"
            :disabled="loading"
          >

            <span v-if="loading">
              Memproses...
            </span>

            <span v-else>
              Sign in
            </span>

            <ArrowRight
              v-if="!loading"
              :size="18"
            />

          </button>

        </form>


        <div class="login-footer">
          © 2026 SIKAP Assalaam. All rights reserved.
        </div>

      </div>

    </section>

  </div>
</template>


<script setup>
import { ref } from 'vue'

import {
  GraduationCap,
  User,
  LockKeyhole,
  Eye,
  EyeOff,
  CircleAlert,
  ArrowRight
} from 'lucide-vue-next'


const username = ref('')
const password = ref('')

const showPassword = ref(false)
const loading = ref(false)
const errorMessage = ref('')


const handleLogin = () => {
  errorMessage.value = ''

  if (!username.value.trim()) {
    errorMessage.value = 'Username wajib diisi.'
    return
  }

  if (!password.value) {
    errorMessage.value = 'Password wajib diisi.'
    return
  }

  console.log('Username:', username.value)
  console.log('Password:', password.value)
}
</script>


<style scoped>

* {
  box-sizing: border-box;
}


/* ========================================
   PAGE
======================================== */

.login-page {
  min-height: 100vh;

  display: grid;
  grid-template-columns: 50% 50%;

  background: #f8fafc;

  overflow: hidden;
}


/* ========================================
   LEFT
======================================== */

.login-left {
  position: relative;

  min-height: 100vh;

  padding: 45px 65px;

  background:
    linear-gradient(
      145deg,
      #0757c9 0%,
      #086bdc 50%,
      #1687e8 100%
    );

  color: white;

  overflow: hidden;
}


/* ========================================
   LOGO
======================================== */

.brand {
  position: relative;
  z-index: 5;

  display: flex;
  align-items: center;

  gap: 12px;
}

.brand-logo {
  width: 48px;
  height: 48px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 13px;

  background: white;

  color: #1267d9;

  box-shadow:
    0 8px 20px rgba(0, 0, 0, 0.12);
}

.brand strong {
  display: block;

  font-size: 23px;
  line-height: 1;

  letter-spacing: 1px;
}

.brand span {
  display: block;

  margin-top: 4px;

  font-size: 14px;

  color: rgba(255, 255, 255, 0.85);
}


/* ========================================
   WELCOME
======================================== */

.welcome-content {
  position: relative;
  z-index: 4;

  max-width: 500px;

  margin-top: 145px;
}

.welcome-small {
  display: block;

  margin-bottom: 10px;

  font-size: 25px;

  font-weight: 400;
}

.welcome-content h1 {
  margin: 0;

  font-size: 50px;
  line-height: 1.1;

  font-weight: 800;

  letter-spacing: -1px;
}

.yellow-line {
  width: 75px;
  height: 4px;

  margin: 25px 0 25px;

  border-radius: 10px;

  background: #facc15;
}

.welcome-content p {
  max-width: 470px;

  margin: 0;

  font-size: 16px;

  line-height: 1.7;

  color: rgba(255, 255, 255, 0.82);
}


/* ========================================
   MOTIF
======================================== */

.pattern {
  position: absolute;

  width: 180px;
  height: 180px;

  opacity: 0.12;

  background-image:
    radial-gradient(
      circle,
      white 1.5px,
      transparent 1.5px
    );

  background-size: 18px 18px;
}

.pattern-top {
  top: -15px;
  left: -10px;
}

.pattern-bottom {
  right: 30px;
  bottom: 60px;

  opacity: 0.08;
}


/* ========================================
   WAVES
======================================== */

.wave {
  position: absolute;

  border-radius: 50%;

  border: 1px solid rgba(255, 255, 255, 0.12);

  pointer-events: none;
}

.wave-one {
  width: 500px;
  height: 500px;

  right: -270px;
  bottom: -250px;
}

.wave-two {
  width: 380px;
  height: 380px;

  right: -190px;
  bottom: -180px;
}


/* ========================================
   RIGHT
======================================== */

.login-right {
  position: relative;

  min-height: 100vh;

  display: flex;
  align-items: center;
  justify-content: center;

  padding: 50px;

  background: #f8fafc;

  background-image:
    radial-gradient(
      circle,
      rgba(37, 99, 235, 0.06) 1px,
      transparent 1px
    );

  background-size: 22px 22px;
}


/* ========================================
   LOGIN CARD
======================================== */

.login-card {
  width: 100%;
  max-width: 500px;

  padding: 45px 48px;

  border-radius: 26px;

  background: white;

  box-shadow:
    0 20px 50px rgba(15, 23, 42, 0.10);
}


/* ========================================
   HEADER
======================================== */

.login-header {
  margin-bottom: 38px;
}

.login-header > span {
  font-size: 14px;

  color: #475569;
}

.login-header strong {
  color: #2563eb;
}

.login-header h2 {
  margin: 8px 0 0;

  font-size: 44px;

  line-height: 1.1;

  color: #0f172a;

  font-weight: 700;
}


/* ========================================
   FORM
======================================== */

.form-group {
  margin-bottom: 22px;
}

.form-group label {
  display: block;

  margin-bottom: 9px;

  font-size: 13px;

  font-weight: 600;

  color: #1e293b;
}

.input-box {
  height: 54px;

  display: flex;
  align-items: center;

  border: 1px solid #d8e0eb;

  border-radius: 10px;

  background: white;

  color: #94a3b8;

  transition: all 0.2s ease;
}

.input-box:focus-within {
  border-color: #2563eb;

  box-shadow:
    0 0 0 3px rgba(37, 99, 235, 0.09);

  color: #2563eb;
}

.input-box > svg {
  flex-shrink: 0;

  margin-left: 16px;
}

.input-box input {
  width: 100%;
  height: 100%;

  padding: 0 14px;

  border: none;
  outline: none;

  background: transparent;

  color: #0f172a;

  font-family: inherit;

  font-size: 14px;
}

.input-box input::placeholder {
  color: #a0aaba;
}


/* ========================================
   PASSWORD
======================================== */

.password-button {
  width: 42px;
  height: 42px;

  display: flex;
  align-items: center;
  justify-content: center;

  margin-right: 5px;

  border: none;

  background: transparent;

  color: #94a3b8;

  cursor: pointer;
}

.password-button:hover {
  color: #2563eb;
}


/* ========================================
   FORGOT
======================================== */

.forgot-password {
  display: flex;
  justify-content: flex-end;

  margin-top: -7px;
  margin-bottom: 22px;
}

.forgot-password a {
  font-size: 12px;

  color: #2563eb;

  text-decoration: none;
}

.forgot-password a:hover {
  text-decoration: underline;
}


/* ========================================
   ERROR
======================================== */

.error-message {
  display: flex;
  align-items: center;

  gap: 8px;

  margin-bottom: 18px;

  padding: 11px 13px;

  border-radius: 9px;

  background: #fff1f2;

  border: 1px solid #fecdd3;

  color: #dc2626;

  font-size: 12px;
}


/* ========================================
   BUTTON
======================================== */

.login-button {
  width: 100%;
  height: 54px;

  display: flex;
  align-items: center;
  justify-content: center;

  gap: 9px;

  border: none;

  border-radius: 10px;

  background: #1267df;

  color: white;

  font-family: inherit;

  font-size: 14px;

  font-weight: 600;

  cursor: pointer;

  box-shadow:
    0 8px 18px rgba(18, 103, 223, 0.22);

  transition: all 0.2s ease;
}

.login-button:hover:not(:disabled) {
  background: #0757c9;

  transform: translateY(-1px);

  box-shadow:
    0 11px 23px rgba(18, 103, 223, 0.28);
}

.login-button:disabled {
  opacity: 0.7;

  cursor: not-allowed;
}


/* ========================================
   FOOTER
======================================== */

.login-footer {
  margin-top: 35px;

  text-align: center;

  font-size: 11px;

  color: #94a3b8;
}


/* ========================================
   RESPONSIVE
======================================== */

@media (max-width: 900px) {

  .login-page {
    grid-template-columns: 42% 58%;
  }

  .login-left {
    padding: 35px;
  }

  .welcome-content {
    margin-top: 110px;
  }

  .welcome-content h1 {
    font-size: 38px;
  }

  .welcome-small {
    font-size: 20px;
  }

  .login-right {
    padding: 30px;
  }

  .login-card {
    padding: 35px;
  }

}


@media (max-width: 700px) {

  .login-page {
    display: block;
  }

  .login-left {
    display: none;
  }

  .login-right {
    min-height: 100vh;

    padding: 25px 18px;
  }

  .login-card {
    padding: 30px 24px;

    border-radius: 20px;

    box-shadow:
      0 10px 30px rgba(15, 23, 42, 0.08);
  }

  .login-header {
    margin-bottom: 30px;
  }

  .login-header h2 {
    font-size: 36px;
  }

}
</style>