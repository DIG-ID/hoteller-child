<?
$digid_site_lang = apply_filters( 'wpml_current_language', null );
switch ($digid_site_lang) :
	case 'de' :
		?>
		<a class="digid-header-booking-btn" href="https://www.simplebooking.it/ibe/hotelbooking/search?hid=6860&lang=DE#guests=A&in=2021-1-4&out=2021-1-5&coupon=" target="_blank">
			<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 13 10">
				<path d="M8.802 0c-.938 0-1.698.77-1.698 1.719v.57L.242 7.63a.632.632 0 0 0-.114.877.615.615 0 0 0 .867.115L2.435 7.5h2.569l.9 2.209c.096.24.368.354.603.256a.472.472 0 0 0 .253-.611L6.005 7.5H6.857l.899 2.21c.097.241.369.354.604.257a.472.472 0 0 0 .253-.612l-.81-1.992A3.75 3.75 0 0 0 10.5 3.75V2.344l1.554-.393a.31.31 0 0 0 .223-.379A1.238 1.238 0 0 0 11.08.625h-.967A1.693 1.693 0 0 0 8.803 0Zm0 1.25a.458.458 0 0 1 .428.29.474.474 0 0 1-.25.612.458.458 0 0 1-.606-.254.474.474 0 0 1 .25-.612.458.458 0 0 1 .178-.036Z" fill="#2795A2"/>
			</svg>
			<?php echo esc_html( 'Jetzt Buchen' ); ?>
		</a>
		<?php
		break;
	case 'en' :
		?>
		<a class="digid-header-booking-btn" href="https://www.simplebooking.it/ibe/hotelbooking/search?hid=6860&lang=EN&_gl=1" target="_blank">
			<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 13 10">
				<path d="M8.802 0c-.938 0-1.698.77-1.698 1.719v.57L.242 7.63a.632.632 0 0 0-.114.877.615.615 0 0 0 .867.115L2.435 7.5h2.569l.9 2.209c.096.24.368.354.603.256a.472.472 0 0 0 .253-.611L6.005 7.5H6.857l.899 2.21c.097.241.369.354.604.257a.472.472 0 0 0 .253-.612l-.81-1.992A3.75 3.75 0 0 0 10.5 3.75V2.344l1.554-.393a.31.31 0 0 0 .223-.379A1.238 1.238 0 0 0 11.08.625h-.967A1.693 1.693 0 0 0 8.803 0Zm0 1.25a.458.458 0 0 1 .428.29.474.474 0 0 1-.25.612.458.458 0 0 1-.606-.254.474.474 0 0 1 .25-.612.458.458 0 0 1 .178-.036Z" fill="#2795A2"/>
			</svg>
			<?php echo esc_html( 'Book now' ); ?>
		</a>
		<?php
		break;
	case 'fr' :
		?>
		<a class="digid-header-booking-btn" href="https://www.simplebooking.it/ibe/hotelbooking/search?hid=6860&lang=FR&_gl=1" target="_blank">
			<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 13 10">
				<path d="M8.802 0c-.938 0-1.698.77-1.698 1.719v.57L.242 7.63a.632.632 0 0 0-.114.877.615.615 0 0 0 .867.115L2.435 7.5h2.569l.9 2.209c.096.24.368.354.603.256a.472.472 0 0 0 .253-.611L6.005 7.5H6.857l.899 2.21c.097.241.369.354.604.257a.472.472 0 0 0 .253-.612l-.81-1.992A3.75 3.75 0 0 0 10.5 3.75V2.344l1.554-.393a.31.31 0 0 0 .223-.379A1.238 1.238 0 0 0 11.08.625h-.967A1.693 1.693 0 0 0 8.803 0Zm0 1.25a.458.458 0 0 1 .428.29.474.474 0 0 1-.25.612.458.458 0 0 1-.606-.254.474.474 0 0 1 .25-.612.458.458 0 0 1 .178-.036Z" fill="#2795A2"/>
			</svg>
			<?php echo esc_html( 'Réserver' ); ?>
		</a>
		<?php
	break;
endswitch;