// ================================
// AMBIL USER YANG SEDANG LOGIN
// ================================

export const getUser = () => {
  const userData = localStorage.getItem('user')

  if (!userData) {
    return null
  }

  try {
    return JSON.parse(userData)
  } catch (error) {
    console.error('Data user tidak valid:', error)

    localStorage.removeItem('user')

    return null
  }
}


// ================================
// AMBIL TOKEN
// ================================

export const getToken = () => {
  return localStorage.getItem('token')
}


// ================================
// CEK SUDAH LOGIN ATAU BELUM
// ================================

export const isAuthenticated = () => {
  return !!getToken() && !!getUser()
}


// ================================
// CEK ROLE SISWA
// ================================

export const isStudent = () => {
  const user = getUser()

  return user?.role === 'siswa'
}


// ================================
// CEK ROLE STAFF
// ================================

export const isStaff = () => {
  const user = getUser()

  return user?.role === 'staf'
}


// ================================
// AMBIL JABATAN STAFF
// ================================

export const getStaffPosition = () => {
  const user = getUser()

  if (user?.role !== 'staf') {
    return null
  }

  return user?.staff?.jabatan ?? null
}


// ================================
// CEK JABATAN STAFF
// ================================

export const hasStaffPosition = (position) => {
  return getStaffPosition() === position
}


// ================================
// LOGOUT
// ================================

export const logout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
}