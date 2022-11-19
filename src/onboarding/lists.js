import { __ } from '@wordpress/i18n';

import apiFetch from '@wordpress/api-fetch';

import { render, Component, Fragment, useState, useCallback, useEffect, useRef } from '@wordpress/element';

import {
	__experimentalHeading as Heading,
	__experimentalDivider as Divider,
	__experimentalSpacer as Spacer,
	__experimentalInputControl as InputControl,
	PanelBody,
	PanelRow,
	Button,
	Notice,
} from '@wordpress/components';

import { withRouter } from 'react-router';

const OnboardingLists = props => {

	return (
		<>

		<Heading level={2}>Set your default newsletter settings</Heading>

		<p className="mailerglue-text-bigger">
			Don't have an account yet? <a href="#">Sign up</a>
		</p>

		</>
	);

}

export default withRouter(OnboardingLists);