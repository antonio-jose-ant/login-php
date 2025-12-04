// src/components/LoginForm.jsx
import { useState } from "react";
import "../assets/css/login.css";
import inputFlotante from "./inputFlotante";

export default function LoginForm() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [message, setMessage] = useState("");

  //mediante una promesa con async previene acciones del  formulario valida los datos en las constantes antes declaradas
  const handleSubmit = async (e) => {
    e.preventDefault();

    const formData = new FormData(); // <--- ESTO FALTABA
    formData.append("User", email);
    formData.append("Pass", password);

    if (!email || !password) {
      setMessage("Por favor completa todos los campos.");
      return;
    }

    try {
      const res = await fetch(
        "http://localhost/LOGIN-PHP/backend/public/index.php/login",
        {
          method: "POST",
          body: formData,
        }
      );

      const data = await res.json();
      if(data.status=="error"){
        setMessage(data.message);
      }
      // console.log(data);
    } catch (error) {
      setMessage(error);
      console.error(error);
    }
  };

  // crea un fomulario agrega clases y con la funcion nativa html onSubmit llama a  handleSubmit para inciar el proceso
  return (
    <div className="Grid login_distribucion">
      <div className="Form-Login flex FlexDCol">
        <h2>Iniciar Sesión</h2>
        <form onSubmit={handleSubmit}>
          <div className="input-wrapper">
            <label htmlFor="User">
              Usuario
              <span></span>
            </label>
            <input
              name="user"
              id="User"
              type="email"
              value={email}
              onChange={(e) => setEmail(inputFlotante(e))}
              onFocus={inputFlotante}
              onBlur={inputFlotante}
            />
          </div>
          <div className="input-wrapper">
            <label htmlFor="Pass">
              Contraseña
              <span></span>
            </label>
            <input
              name="Pass"
              id="Pass"
              type="password"
              value={password}
              onChange={(e) => setPassword(inputFlotante(e))}
              onFocus={inputFlotante}
              onBlur={inputFlotante}
            />
          </div>
          <button type="submit" className="w-100">
            Entrar
          </button>
        </form>
        {message && <p className="message">{message}</p>}
      </div>
      <div className="Image-Login Grid">
        <div className="image-Login w-100 h-100 flex">
          {/* <img src="./src/assets/img/login_img.png" alt="" /> */}
        </div>
        <footer className="Footer-login w-100 h-100 flex color-1">
          <p>Copyright ©2025 Jose antonio Rodriguez Segura</p>
        </footer>
      </div>
    </div>
  );
}
