import { __ } from '@wordpress/i18n';

import apiFetch from '@wordpress/api-fetch';

import { render, Component, Fragment, useState, useEffect } from '@wordpress/element';

import { HashRouter, Switch, Route, Link, NavLink } from 'react-router-dom';

import MailerGlueTopBar from '../top-bar';
import OnboardingConnect from './connect';
import OnboardingLists from './lists';

export const Onboarding = props => {

	const [ step, setStep ] = useState( 1 );

	return (
		<>
			<MailerGlueTopBar />
			<div className="mailerglue-setup">

				<HashRouter>
					<Switch>
						<Route exact path="/" render={ (props) => <OnboardingConnect setStep={setStep} /> } />
						<Route exact path="/settings" render={ (props) => <OnboardingLists setStep={setStep} /> } />
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