<?php
// Este módulo requiere tener inicializados las variables $cantidad (cantidad de
// artículos totales), $maxpagina (mayor página posible), $pagina (página actual) y
// $otrosparametros (por ejemeplo, para clientes.php, es necesario indicar los criterios
// de búsqueda en cada página
?>

					<div id="paginacion"  <?php if ($cantidad<=0) echo 'class="oculto"'; ?>>
						<div id="botones">
<?php
							
							// Cantidad de botones que se muestran a cada lado de la página actual.
							$numbotones = 3;
							
							// Comprobamos si hay que habilitar el botón anterior.
							if ($pagina <= 1)
							echo'							<div class="bloqueado">&#171; anterior</div>',"\n";
							else
							echo'							<div><a href="'.$otrosparametros.'&amp;pagina='.($pagina-1).'">&#171; anterior</a></div>',"\n";
							
							// Comprobamos si hay que mostrar el botón hacia la primera página (por si nos hemos alejado mucho).
							if ($pagina>$numbotones+1)
							echo'							<div><a href="'.$otrosparametros.'&amp;pagina=1">1</a></div>',"\n";
							if ($pagina>$numbotones+2)
							echo'							<div class="separacion">...</div>',"\n";
							
							// Mostramos los botones correspondientes.
							for ($i=($pagina-$numbotones); $i<=($pagina+$numbotones); $i++)
							{
								if ($i >= 1 && $i <= $maxpagina)
								{
									if ($i != $pagina)
									{
									echo'							<div><a href="'.$otrosparametros.'&amp;pagina='.$i.'">'.$i.'</a></div>',"\n";
									}
									else
									{
									echo'							<div class="actual">'.$i.'</div>',"\n";									
									}						
								}
							}
							
							// Comprobamos si hay que mostrar el botón hacia la última página (si no nos hemos acercado al final mucho).
							if ($pagina+$numbotones < $maxpagina-1)
							echo'							<div class="separacion">...</div>',"\n";
							if ($pagina+$numbotones < $maxpagina)						
							echo'							<div><a href="'.$otrosparametros.'&amp;pagina='.$maxpagina.'">'.$maxpagina.'</a></div>',"\n";
							
							// Comprobamos si hay que habilitar el botón siguiente.
							if ($pagina<$maxpagina)
							echo'							<div><a href="'.$otrosparametros.'&amp;pagina='.($pagina+1).'">siguiente &#187;</a></div>',"\n";
							else
							echo'							<div class="bloqueado">siguiente &#187;</div>',"\n";
							
							?>
						</div>
					</div>
