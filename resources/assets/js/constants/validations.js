import * as yup from "yup";

export const esquemaSucursal = yup.object().shape({
  nombre: yup
    .string()
    .required("El nombre de la sucursal es obligatorio")
    .max(50, "El nombre no puede tener más de 50 caracteres")
    .matches(
      /^[a-zA-Z0-9\s]+$/,
      "El nombre no puede contener caracteres especiales"
    ),

  direccion: yup.string().required("La dirección es obligatoria"),

  correo: yup
    .string()
    .email("Ingrese una dirección de correo electrónico válida")
    .required("El correo es obligatorio"),

  telefono: yup
    .string()
    .required("El teléfono es obligatorio")
    .matches(/^[0-9]{8}$/, "El teléfono debe contener exactamente 8 números"),

  departamento: yup.string().required("El departamento es obligatorio"),
});

export const esquemaMoneda = yup.object().shape({
  nombre: yup
    .string()
    .required("El nombre de la moneda es obligatorio")
    .max(50, "El nombre no puede tener más de 50 caracteres")
    .matches(
      /^[a-zA-Z0-9\s]+$/,
      "El nombre no puede contener caracteres especiales"
    ),

  pais: yup.string().required("El país es obligatorio"),
  simbolo: yup.string().required("El código de la moneda es obligatorio"),

  tipo_cambio: yup
    .number()
    .typeError("El tipo de cambio debe ser un número")
    .required("El tipo de cambio es obligatorio")
    .min(1, "El tipo de cambio no puede ser igual o menor a cero"),
});

export const esquemaKits = yup.object().shape({
  nombre: yup
    .string()
    .required("El nombre es obligatorio")
    .max(80, "El nombre no puede tener más de 80 caracteres"),
  codigo: yup
    .string()
    .required("El codigo es obligatorio")
    .max(100, "El codigo no puede tener más de 100 caracteres"),
  precio: yup
    .number()
    .required("El precio es obligatorio")
    .min(1, "El precio no puede ser menor o igual que 0")
    .typeError("Debe ser un número"),
  fecha_final: yup.string().required("La fecha final es obligatoria"),
  
});

export const esquemaOfertasEspeciales = yup.object().shape({
  nombre: yup
    .string()
    .required("El nombre es obligatorio")
    .max(80, "El nombre no puede tener más de 80 caracteres"),
  fecha_final: yup.string().required("La fecha final es obligatoria"),

  precio_r1: yup
  .number()
  .required("El precio del rango 1 es obligatorio")
  .min(1, "El precio no puede ser menor o igual que 0")
  .typeError("Debe ser un número"),
  rango_inicio_r1: yup
  .number()
  .required("El rango inicio 1 es obligatorio")
  .min(1, "El rango no puede ser menor o igual que 0")
  .typeError("Debe ser un número"),
  rango_final_r1: yup
    .number()
    .required("El precio es obligatorio")
    .min(yup.ref('rango_inicio_r1'), "El rango final debe ser mayor que el rango inicial")
    .typeError("Debe ser un número"),
  
    precio_r2: yup
    .number()
    .required("El precio del rango 2 es obligatorio")
    .min(1, "El precio no puede ser menor o igual que 0")
    .typeError("Debe ser un número"),
    rango_inicio_r2: yup
    .number()
    .required("El precio es obligatorio")
    .min(yup.ref('rango_final_r1'), "El rango inicio 2 debe ser mayor al rango final 1")
    .typeError("Debe ser un número"),
    rango_final_r2: yup
    .number()
    .required("El precio es obligatorio")
    .min(yup.ref('rango_inicio_r2'), "El rango final 2 debe ser mayor al rango inicio 2")
    .typeError("Debe ser un número"),
  
    precio_r3: yup
    .number()
    .required("El precio es obligatorio")
    .min(1, "El precio no puede ser menor o igual que 0")
    .typeError("Debe ser un número"),
    rango_inicio_r3: yup
    .number()
    .required("El precio es obligatorio")
    .min(yup.ref('rango_final_r2'), "El rango inicio 3 debe ser mayor al rango final 2")
    .typeError("Debe ser un número"),
    rango_final_r3: yup
    .number()
    .required("El precio es obligatorio")
    .min(yup.ref('rango_inicio_r3'), "El rango final 3 debe ser mayor al rango inicio 3")
    .typeError("Debe ser un número"),
  
});

export const esquemaOfertas = yup.object().shape({
  nombre: yup
    .string()
    .required("El nombre es obligatorio")
    .max(80, "El nombre no puede tener más de 80 caracteres"),
  porcentaje: yup
    .number()
    .required("El porcentaje es obligatorio")
    .min(0, "El porcentaje no puede ser menor que 0")
    .max(100, "El porcentaje no puede ser mayor que 100")
    .typeError("Debe ser un número"),
  fecha_final: yup.string().required("La fecha final es obligatoria"),
});
export const esquemaArticulos = yup.object().shape({
  nombre: yup
    .string()
    .required("El nombre es obligatorio")
    .max(80, "El nombre no puede tener más de 80 caracteres"),
  descripcion: yup.string().required("La descripción es obligatoria"),
  nombre_generico: yup.string().required("El nombre generico es obligatorio"),
  unidad_envase: yup
    .number()
    .required("La cantidad de unidades por paquete es obligatoria")
    .typeError("Debe ingresar un número válido")
    .min(
      0.01,
      "El cantidad de unidades por paquete no puede ser menor o igual a 0"
    ),

  precio_costo_unid: yup
    .number()
    .required("La cantidad del costo por unidad es obligatoria")
    .typeError("Debe ingresar un número válido")
    .min(
      0.01,
      "El cantidad del costo por unidad no puede ser menor o igual a 0"
    ),

  precio_costo_paq: yup
    .number()
    .required("La cantidad del costo por paquete es obligatoria")
    .typeError("Debe ingresar un número válido")
    .min(
      0.01,
      "El cantidad de costo por paquete no puede ser menor o igual a 0"
    ),

  precio_venta: yup
    .number()
    .required("El precio de venta es obligatoria")
    .typeError("Debe ingresar un número válido")
    .min(0.01, "El precio de venta no puede ser menor o igual a 0"),
  precio_uno: yup.number().typeError("Debe ingresar un número válido"),
  precio_dos: yup.number().typeError("Debe ingresar un número válido"),
  precio_tres: yup.number().typeError("Debe ingresar un número válido"),
  precio_cuatro: yup.number().typeError("Debe ingresar un número válido"),
  stock: yup
    .number()
    .required("El stock es obligatorio")
    .typeError("Debe ingresar un número válido")
    .min(0.01, "El stock minimo no puede ser menor o igual a 0"),

  costo_compra: yup
    .number()
    .required("El costo de compra es obligatorio")
    .typeError("Debe ingresar un número válido")
    .min(0.01, "El costo de compra no puede ser menor o igual a 0"),

  codigo: yup.string().required("El código es obligatorio"),
  codigo_alfanumerico: yup.string(),
  descripcion_fabrica: yup.string(),
  idcategoria: yup.number().required("El campo Línea es obligatorio"),
  idmarca: yup.number().required("El campo Marca es obligatorio"),
  idindustria: yup.number().required("El campo Industria es obligatorio"),
  idgrupo: yup.number().required("El campo Grupo o Familia es obligatorio"),
  idproveedor: yup.number().required("El campo Proveedor es obligatorio"),
  idmedida: yup.number().required("El campo Medida es obligatorio"),
});

export const esquemaAlmacen = yup.object().shape({
  nombre_almacen: yup
    .string()
    .required("El nombre del almacén es obligatorio")
    .max(80, "El nombre del almacén no puede tener más de 80 caracteres"),
    ubicacion: yup.string().required("La ubicación física es obligatoria"),
    encargado: yup.string().required("El nombre del encargado es obligatorio"),
    telefono: yup
      .string()
      .required("El teléfono es obligatorio")
      .matches(/^[0-9]{8}$/, "El teléfono debe contener exactamente 8 números"),
    sucursal: yup.string().required("El nombre de la sucursal es obligatorio"),
    observacion: yup
      .string()
      .max(255, "Las observaciones no pueden tener más de 255 caracteres"),
});

export const esquemaCliente = yup.object().shape({
  nombre: yup
    .string()
    .required("El nombre del cliente es obligatorio")
    .max(80, "El nombre del cliente no puede tener más de 80 caracteres"),
  direccion: yup.string().required("La dirección del cliente es obligatoria"),
  tipo_documento: yup.string().required("El tipo de documento es obligatorio"),
  num_documento: yup.string().required("El número de documento es obligatorio"),
  telefono: yup.string().required("El teléfono es obligatorio"),
  email: yup
    .string()
    .email("El correo electrónico debe tener un formato válido")
    .required("El correo electrónico es obligatorio"),
});

export const esquemaPuntoDeVenta = yup.object().shape({
  nombre: yup
    .string()
    .required("El nombre del punto de venta es obligatorio")
    .max(
      100,
      "El nombre del punto de venta no puede tener más de 100 caracteres"
    ),
  nit: yup.string().required("El NIT del punto de venta es obligatorio"),
  descripcion: yup
    .string()
    .required("La descripción del punto de venta es obligatoria"),
  idtipopuntoventa: yup
    .string()
    .required("El tipo de punto de venta es obligatorio"),
  idsucursal: yup.string().required("La sucursal es obligatoria"),
});

export const esquemaBanco = yup.object().shape({
  nombre_cuenta: yup.string().required("El nombre de la cuenta es obligatorio"),

  nombre_banco: yup.string().required("El nombre del banco es obligatorio"),
  numero_cuenta: yup
    .number()
    .typeError("El número de cuenta debe ser un número")
    .required("El número de cuenta es obligatorio")
    .min(1, "El número de cuenta no puede ser igual o menor a cero"),
  tipo_cuenta: yup.string().required("El tipo de cuenta es obligatorio"),
});
