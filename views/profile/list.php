<div id="container">
	
	<div class="status_circle" style="background-color: #ff0000"></div>
	&nbsp; Fehlerhafte Verarbeitung<br>
	<div class="status_circle" style="background-color: #ffff00"></div>
	&nbsp; Befindet sich aktuell in Verarbeitung<br>
	<div class="status_circle" style="background-color: #00ff00"></div>
	&nbsp; Verarbeitung erfolgreich
	
	<br/><br/>
	<b>Hinweise:</b>
	<br/>
	<ul>
		<li>
			Sie k&ouml;nnen den Verarbeitungs-Status auch einsehen, indem Sie mit der Maus
			&uuml;ber den entsprechenden Kreis in der Tabelle unten unter <b>Status</b> fahren.
		</li>
		<li>
			Zur Einsicht aller <b>Meta-Daten eines Videos</b> klicken Sie auf die entsprechende Zeile. <br/>
			Es erscheint dann eine erweiterte Ansicht mit n&auml;hren Informationen zum Video.
		</li>
	</ul>
	<br/><br/>
	
	<table class="default">
		<thead>
			<tr>
				<th style="text-align: center;">Status</th>
				<th style="">Titel</th>
				<th style="text-align: center;">Ver&ouml;ffentlicht</th>
				<th style="">Serie</th>
				<th style=""></th>
				<th style="">Aufzeichungsdatum</th>
				<th colspan="3" style="text-align: center;"><!--Optionen--></th>

			</tr>
		</thead>
		<tbody>
		<?php
			$even = false;
			foreach ($this->videos as $video)
			{
				
				switch($video['processing_state']) {
					case "SUCCEEDED":
						$webm_color = '#00ff00';
						$title = 'Verarbeitung erfolgreich';
						break;
					case "RUNNING":
						$webm_color = '#ffff00';
						$title = 'Befindet sich aktuell in Verarbeitung';
						break;
					default:
						$webm_color = '#ff0000';
						$title = 'Fehlerhafte Verarbeitung';
				}
				$even = !$even;
				
		?>
				<tr class="<? if($even){print("hover_even");}else{print("hover_odd");} ?>" 
						onClick="toggleVideoInfoRow('<? print($video['identifier']) ?>')" 
						 class="videoHeaderRow" 
						 id="videoHeaderRow_<? print($video['identifier']) ?>">
					<!-- Status -->
					<td style="text-align: center;">
					<?php
						echo 
							'<div class="status_circle" '.
									'style="background-color: '.$webm_color.';" '.
									'title="'.$title.'" alt="'.$title.'"></div>';
					?>
					</td>
					<!-- Titel -->
					<td>
						<? print($video['title']) ?>
					</td>
					<!-- Veröffenticht -->
					<td style="text-align: center;">
					<?php
						echo 
						(
							in_array('engage-player', $video['publication_status']) ?
							'Ja' : 'Nein'
						)
					?>
					</td>
					<!-- Serie (mit Link zur Veranstaltung) -->
					<td>
						<? print($video['series_data']['title']); ?>
					</td>
					<!-- Link zur Veranstaltung -->
					<td>
						<?php
							if ( count($video['series_data']['seminars']) > 0)
								echo 
									'<a href="'.
										URLHelper::getScriptLink(
											'plugins.php/opencast/course', 
											array('cid' => $video['series_data']['seminars'][0]['Seminar_id'])
										).
										'" '.
											'title="Zur Veranstaltung" target="_blank">'.
										Assets::img('icons/16/blue/seminar.png').
									'</a>';
						?>
					</td>
					<!-- Aufzeichungsdatum -->
					<td>
						<? print(str_replace(array('T','Z'), array(' um ',''), $video['start'])) ?>
					</td>
					<!-- BUTTONS -->
					<!-- Button: Video herunterladen -->
					<td>
					<?php
#						echo 
#							'<a href="'.$video['downloadLink'].'" '.
#									'title="Video herunterladen">'.
#								Assets::img('icons/16/blue/download.png').
#							'</a>';
					?>
					</td>
					<!-- Button: Video-Meta-Daten bearbeiten -->
					<td>
					<?php
#						echo 
#							'<a href="/edit/" '. 
#									'title="Meta-Daten bearbeiten">'.
#								Assets::img('icons/16/blue/edit.png').
#							'</a>';
					?>
					</td>
					<!-- Button: Video zurückziehen -->
               <td>
					<?php
#						echo
#							'<a href="'.
#									PluginEngine::getLink(
#										"VideoTUBS", 
#										array("vid" => $video['id'], "user" => "true"), 
#										'delete_video'
#									).'" '. 
#									'title="Video l&ouml;schen">'.
#								Assets::img('icons/16/blue/trash.png').
#							'</a>';
					?>
					</td>
				</tr>
				
				<?php
					# Diese Zeile mit weiteren Informationen und Einstellungs-
					# möglichkeiten zum Video wird bei OnClick der darüber liegenden
					# Zeile angezeigt:
				?>
				<tr id="videoInfoRow_<? print($video['identifier']) ?>" style="display: none;">
					<td colspan="9" style="background-color: #ededed;">
						<table>
							<tr>
								<td width="320" style="padding: 0px;" id="videoInfoCell_<? print($video['identifier']) ?>">
									<? 
									# Hier wird bei Klick auf die drüber liegende Zeile 
									# das I-Frame mit dem zugehörigen Video geladen: 
									?>
								</td>
								<td style="vertical-align: top; padding: 0px 7px 0px 7px;">
									<table style="cell-spacing: 0px; border-padding: 0px;">
										<tr style="vertical-align: top;">
											<td style="font-weight: bold; padding: 0px;" width="200">
												Betreff:
											</td>
											<td style="padding: 0px;">
												<? print(utf8_decode($video['subjects'][0])) ?>
											</td>
										</tr>
										<tr style="vertical-align: top;">
											<td style="font-weight: bold; padding: 0px;">
												Beschreibung:
											</td>
											<td style="padding: 0px;">
												<? print(utf8_decode($video['description'])) ?>
											</td>
										</tr>
										<tr style="vertical-align: top;">
											<td style="font-weight: bold; padding: 0px;">
												Raum:
											</td>
											<td style="padding: 0px;">
												<? print(utf8_decode($video['location'])) ?>
											</td>
										</tr>
										<tr>
											<td style="font-weight: bold; padding: 0px; padding-top: 10px;">
												Dauer:
											</td>
											<td style="padding: 0px; padding-top: 10px;">
												<? print($video['duration']) ?>
											</td>
										</tr>
										<tr style="vertical-align: top;">
											<td style="font-weight: bold; padding: 0px;">
												Sprache:
											</td>
											<td style="padding: 0px;">
											<?php
												if
												( 
													array_key_exists(
														$video['metadata'][0]['fields'][3]['value'],
														$this->lang
													)
												)
													echo $this->lang[$video['metadata'][0]['fields'][3]['value']];
												else
													echo $video['metadata'][0]['fields'][3]['value'];
											?>
											</td>
										</tr>
										<tr>
											<td style="padding: 0px; font-weight: bold;">
												Aufrufe:
											</td>
											<td style="padding: 0px;">
												?
											</td>
										</tr>
										<tr>
											<td style="font-weight: bold; padding: 0px; padding-top: 10px; vertical-align: top;">
												Mitwirkende:
											</td>
											<td style="padding: 0px; padding-top: 10px;">
											<?php 
												$ms = $video['contributor'];
												for($m = 0; $m < count($ms); $m++) 
												{
													if ( is_array($ms[$m]) ) {
														echo '<a href="'.
															URLHelper::getScriptLink(
																'dispatch.php/profile', 
																array('username' => $ms[$m]['username'])
															).
															'" '.
															'title="Zum Profil des Mitwirkenden" '.
															'target="_blank" '.
															'>'.
																$ms[$m]['Vorname'].' '.$ms[$m]['Nachname'].
															'</a>';
													}
													else
														echo $ms[$m];
													if ($m < (count($ms) - 1) )
														echo ', ';
												}
											?>
											</td>
										</tr>
										<tr>
											<td style="font-weight: bold; padding: 0px;">
												Rechte:
											</td>
											<td style="padding: 0px;">
												<? print(utf8_decode($video['metadata'][0]['fields'][4]['value'])) ?>
											</td>
										</tr>
										<tr>
											<td style="font-weight: bold; padding: 0px;">
												Lizenz:
											</td>
											<td style="padding: 0px;">
												<? print(utf8_decode($video['metadata'][0]['fields'][5]['value'])) ?>
											</td>
										</tr>
										<tr>
											<td style="font-weight: bold; padding: 0px;">
												Quelle:
											</td>
											<td style="padding: 0px;">
												<? print(utf8_decode($video['metadata'][0]['fields'][13]['value'])) ?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
		<?php
			}
		?>
		</tbody>
	</table>
</div>






<script type="text/javascript">
	function toggleVideoInfoRow(
			videoID
		) 
	{
		$("[id^=videoHeaderRow_]").css("background-color", "");
		$("[id^=videoHeaderRow_]").css("font-weight", "");
		
		if ( $("#videoInfoRow_"+videoID+"").css('display') == 'none' )
		{
			$("[id^=videoInfoRow_]").css("display", "none");
			$("[id^=videoInfoCell_]").html('');
			var content =
					'<iframe src="https://opencast-present.rz.tu-bs.de/'+
								'engage/theodul/ui/core.html?'+
							'id='+videoID+''+
							'&mode=embed" '+
							'style="'+
								'border:0px #FFFFFF none; '+
								'margin-right: 0px;'+
							'" '+
							'name="Opencast media player" '+
							'scrolling="no" '+
							'frameborder="2px" '+
							'marginheight="0px" '+
							'marginwidth="0px" '+
//							'width="480" '+
							'width="478" '+
							'height="270" '+
							'allowfullscreen="true" '+
							'webkitallowfullscreen="true" '+
							'mozallowfullscreen="true"'+
						'>'+
					'</iframe>';
			$("#videoInfoCell_"+videoID+"").html(content);
			$("#videoInfoRow_"+videoID+"").css("display", "");
			// Farbliche Hervorhebung der Zeile:
			$("#videoHeaderRow_"+videoID+"").css("background-color", "#bac4db");
			$("#videoHeaderRow_"+videoID+"").css("font-weight", "bold");
		}
		else {
			$("#videoInfoRow_"+videoID+"").css("display", "none");
			$("#videoInfoCell_"+videoID+"").html('');
		}
	}
</script>
