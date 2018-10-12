<ul class="countries tree">

						<?php if (!empty($countries)) { ?> 
						<?php foreach ($countries as $country) { ?> 
		 					<li>
		 						<a><?php echo $country->country_name; ?></a>
		 						<?php 
		 							$divisions = $this->country->get_divisions($country->id);
		 							if (!empty($divisions)) {
								?>
									<ul class="divisions">
										<?php foreach ($divisions as $division) {  ?>
											<li>
												<a><?php echo $division->division_name; ?></a>
												<?php 
						 							$districts = $this->division->get_districts($division->id);
						 							if (!empty($districts)) {
												?>
												<ul class="districts">
													<?php foreach ($districts as $district) { ?>
														<li>
															<a><?php echo $district->district_name; ?></a>
															<?php 
									 							$areas = $this->district->get_areas($district->id);
									 							if (!empty($areas)) {
															?>
															<ul class="areas">
																<?php foreach ($areas as $area){ ?> 
																	<li>
																		<a><?php echo $area->area_name; ?></a>
																		<?php
																			$sub_areas = $this->area->get_sub_areas($area->id);
																			if (!empty($sub_areas)) {
																		?>
																		<ul class="sub_areas">
																			<?php foreach ($sub_areas as $sub_area) {  ?>
																				<li>
																					<a><?php echo $sub_area->sub_area_name; ?></a>
																					<?php 
																						$roads = $this->sub_area->get_roads($sub_area->id);
																						if (!empty($roads)) { 

																					?>
																					<ul class="roads">
																						<?php foreach ($roads as $road) { ?>
																							<li><a><?php echo $road->road_name; ?></a></li>
																						<?php } ?>
																					</ul>
																					<?php } ?>
																				</li>
																			<?php } ?>
																		</ul>
																		<?php } ?>
																	</li>
																<?php } ?>
															</ul>
															<?php } ?>
														</li>
													<?php } ?>
												</ul>
												<?php } ?>
											</li>
										<?php } ?>
									</ul>
								<?php
		 							}
		 						?>
		 					</li>
		 				<?php } ?>
						<?php } ?>
					</ul>