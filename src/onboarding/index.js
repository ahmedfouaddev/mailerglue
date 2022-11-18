import { __ } from '@wordpress/i18n';

import apiFetch from '@wordpress/api-fetch';

import { render, Component, Fragment, useState, useEffect } from '@wordpress/element';

import { MailerGlueTopBar } from '../top-bar';
import { OnboardingConnect } from './connect';

export const Onboarding = props => {

	const [ step, setStep ] = useState( 1 );

	return (
		<>
			<MailerGlueTopBar />
			<div className="mailerglue-setup">
				{ step == 1 && <OnboardingConnect /> }
			</div>
			<div className="mailerglue-bottom">
				<a href="#" className="mailerglue-link-muted">Skip onboarding</a>
			</div>
		</>
	);

}

var rootElement = document.getElementById( 'mailerglue-onboarding' );

if ( rootElement ) {
	render(
		<Onboarding />,
		rootElement
	);
}