import { __ } from '@wordpress/i18n';

import apiFetch from '@wordpress/api-fetch';

import { render, Component, Fragment, useState, useEffect } from '@wordpress/element';

import { HashRouter, Switch, Route, Link, NavLink } from 'react-router-dom';

import MailerGlueTopBar from '../top-bar';
import OnboardingConnect from './connect';
import OnboardingLists from './lists';

export const Onboarding = props => {

	const [state, setState] = useState( {
		email: '',
		password: '',
		errors: [],
		sending: false,
		access_token: mailerglue_backend.access_token,
		from_name: mailerglue_backend.from_name,
		from_email: mailerglue_backend.from_email,
	} );

	return (
		<>
			<MailerGlueTopBar />
			<div className="mailerglue-setup">

				<HashRouter>
					<Switch>
						<Route exact path="/" render={ (props) => <OnboardingConnect state={state} setState={setState} /> } />
						<Route exact path="/settings" render={ (props) => <OnboardingLists state={state} setState={setState} /> } />
					</Switch>
				</HashRouter>

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