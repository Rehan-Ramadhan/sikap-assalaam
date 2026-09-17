// user

export const getUser = () => {
  const userData = localStorage.getItem("user");

  if (!userData) {
    return null;
  }

  try {
    return JSON.parse(userData);
  } catch (error) {
    console.error("Data user tidak valid:", error);

    localStorage.removeItem("user");

    return null;
  }
};

// token

export const getToken = () => {
  return localStorage.getItem("token");
};

// status login

export const isAuthenticated = () => {
  return !!getToken() && !!getUser();
};

// role student

export const isStudent = () => {
  return getUser()?.role === "student";
};

// role staff

export const isStaff = () => {
  return getUser()?.role === "staff";
};

// jabatan staff

export const getStaffPosition = () => {
  const user = getUser();

  if (user?.role !== "staff") {
    return null;
  }

  return user?.staff?.jabatan ?? null;
};

// cek jabatan staff

export const hasStaffPosition = (position) => {
  return getStaffPosition() === position;
};

// simpan session

export const saveSession = (token, user) => {
  localStorage.setItem("token", token);
  localStorage.setItem("user", JSON.stringify(user));
};

// hapus session

export const logout = () => {
  localStorage.removeItem("token");
  localStorage.removeItem("user");
};
