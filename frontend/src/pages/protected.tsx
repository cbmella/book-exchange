// pages/protected.tsx

import { useState, useEffect } from "react";
import axios from "axios";

const Protected: React.FC = () => {
  const [isLoggedIn, setIsLoggedIn] = useState<boolean>(false);

  useEffect(() => {
    const token = localStorage.getItem("jwt");
    if (token) {
      setIsLoggedIn(true);
    } else {
      window.location.href = "/login"; // Si no hay token, redirige al inicio de sesión
    }
  }, []);

  const handleLogout = async () => {
    try {
      const token = localStorage.getItem("jwt");
      if (!token) {
        throw new Error("No token found");
      }

      await axios.post("http://localhost:8000/api/logout", {}, {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });

      localStorage.removeItem("jwt"); // Elimina el token del almacenamiento local
      setIsLoggedIn(false);
      window.location.href = "/login"; // Redirige al usuario a la página de inicio de sesión
    } catch (error) {
      console.error("Error al cerrar sesión:", error);
    }
  };

  return (
    <div>
      <h2>Área Protegida</h2>
      {isLoggedIn && (
        <>
          <p>Bienvenido al área protegida. Aquí puedes ver contenido exclusivo.</p>
          <button onClick={handleLogout}>Cerrar Sesión</button>
        </>
      )}
    </div>
  );
};

export default Protected;
