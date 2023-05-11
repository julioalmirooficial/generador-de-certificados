const formulario = document.getElementById("form");
const generate = formulario.querySelector('[type="submit"]');

const msg = (msg, type) => {
  return `
  <div class="${type}" role="alert">
    ${msg}
  </div>
  `;
};

generate.addEventListener("click", (e) => {
  e.preventDefault();

  const key = formulario.querySelector("#key").value;
  const fileExcel = formulario.querySelector("#fileExcel").files[0];
  const fileImage = formulario.querySelector("#fileImage").files[0];
  const to = formulario.querySelector("#to").value;
  const from = formulario.querySelector("#from").value;
  if (key === null || key === "") {
    Swal.fire({
      title: "ADVERTENCIA",
      text: "Ingresa un prefijo para el codigo del certificado en formato .xlsx",
      icon: "warning",
      showCancelButton: false,
      cancelButtonColor: "#d33",
    });
    return;
  }
  if (fileExcel === undefined || fileExcel === "") {
    Swal.fire({
      title: "ADVERTENCIA",
      text: "Selecciona la lista de usuarios desde excel",
      icon: "warning",
      showCancelButton: false,
      cancelButtonColor: "#d33",
    });
    return;
  }
  if (fileImage === undefined || fileImage === "") {
    Swal.fire({
      title: "ADVERTENCIA",
      text: "Selecciona una plantilla de certificado en formato .jpg",
      icon: "warning",
      showCancelButton: false,
      cancelButtonColor: "#d33",
    });
    return;
  }

  document.getElementById("msg").innerHTML =
    //html
    `  
  <button class="btn btn-success" type="button" disabled>
    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    <span>Generando certificado</span>
  </button>
  `;

  const formData = new FormData();
  formData.append("key", key);
  formData.append("fileExcel", fileExcel);
  formData.append("fileImage", fileImage);
  formData.append("to", to);
  formData.append("from", from);

  fetch("./generate.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((response) => {
      if (response.message.includes('Hemos generado')) {
        document.getElementById("msg").innerHTML = msg(
          response.message,
          "alert alert-success"
        );
      } else {
        document.getElementById("msg").innerHTML = msg(
          response.message,
          "alert alert-warning"
        );
      }
    })
    .catch(
      (error) =>
        (document.getElementById("msg").innerHTML = msg(
          error,
          "alert alert-danger"
        ))
    );
});
