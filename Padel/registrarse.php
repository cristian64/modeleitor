<?php
include_once "base.php";
baseSuperior("Registrarse", false);
?>
					<div id="registrarse">
						<h3><span>Datos del nuevo usuario</span></h3>
						<form action="operarregistro.php" method="post" enctype="multipart/form-data" onsubmit="return validarRegistro(this);">
							<table>
								<tr>
									<td class="columna1">E-mail*:</td>
									<td class="columna2"><input type="text" value="" name="email" class="textinput" /></td>
								</tr>
								<tr>
									<td class="columna1">Contraseña*:</td>
									<td class="columna2"><input type="password" value="" name="contrasena" class="textinput" /></td>
								</tr>
								<tr>
									<td class="columna1">Confirmación de contraseña*:</td>
									<td class="columna2"><input type="password" value="" name="contrasena2" class="textinput" /></td>
								</tr>
								<tr>
									<td class="columna1">Nombre y apellidos*:</td>
									<td class="columna2"><input type="text" value="" name="nombre" class="textinput" /></td>
								</tr>
								<tr>
									<td class="columna1">DNI:</td>
									<td class="columna2"><input type="text" value="" name="dni" class="textinput" /></td>
								</tr>
								<tr>
									<td class="columna1">Sexo*:</td>
									<td class="columna2">
										<input type="radio" name="sexo" value="mujer" checked="checked"/> Mujer
										<input type="radio" name="sexo" value="hombre" /> Hombre
									</td>
								</tr>
								<tr>
									<td class="columna1">Dirección:</td>
									<td class="columna2"><input type="text" value="" name="direccion" class="textinput" /></td>
								</tr>
								<tr>
									<td class="columna1">Teléfono:</td>
									<td class="columna2"><input type="text" value="" name="telefono" class="textinput" /></td>
								</tr>
								<tr>
									<td class="columna1"></td>
									<td class="columna2"><input type="submit" value="Registrarse" class="freshbutton-big" /></td>
								</tr>
							</table>
						</form>
					</div>
<?php
baseInferior();
?>