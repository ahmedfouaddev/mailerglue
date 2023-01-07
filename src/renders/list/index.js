import { __ } from '@wordpress/i18n';

import apiFetch from '@wordpress/api-fetch';

import { render, Component, Fragment, useState, useEffect } from '@wordpress/element';

import { HashRouter, Switch, Route, Link, NavLink } from 'react-router-dom';

import {
	__experimentalSpacer as Spacer,
	__experimentalHStack as HStack,
	__experimentalText as Text,
	Flex,
	FlexItem,
	FlexBlock,
	Snackbar,
	Notice
} from '@wordpress/components';

import { ListTitle, ListStats, ListDetails, ListAddSub, ListHistory } from '@components/list';

import { theme } from '@data/theme';

export const List = props => {

	const { list } = mailerglue_backend;

	const [attributes, setAttributes] = useState( {
		id: props.id,
		title: list.title,
		description: list.description,
		showSnackbar: false,
		showError: false,
		snackbarMessage: '',
		errorMessage: '',
	} );

	const { sizes, fontweight, colors, gap } = theme;

	return (
		<Spacer marginBottom={ gap } margin={ gap } className="mailerglue-admin-ui">

			{ attributes.errorMessage && 
			<Notice
				isDismissible={false}
				status="error"
			>{ attributes.errorMessage }</Notice>
			}

			{ attributes.showSnackbar && <Snackbar className="mailerglue-snackbar">{ attributes.snackbarMessage }</Snackbar> }

			<Flex
				gap={ gap }
				align="center"
				justify="space-between"
				style={{ backgroundColor: '#FCFCFC' }}
			>

				<ListTitle attributes={attributes} setAttributes={setAttributes} />

				<ListStats />

			</Flex>

			<Spacer marginBottom={ gap } />

			<Flex
				gap={ gap }
				align="flex-start"
				justify="space-between"
			>
				<FlexItem style={{ flexBasis: '33%', display: "flex", alignItems: "center", padding: '0px' }}>
					<Flex gap={ gap } direction="column" style={ { width: '100%' } }>

						<ListDetails attributes={attributes} setAttributes={setAttributes} />

						<ListAddSub />

					</Flex>
				</FlexItem>

				<ListHistory />

			</Flex>

		</Spacer>
	);

}

var rootElement = document.getElementById( 'mailerglue-edit-list' );

if ( rootElement ) {
	var id = rootElement.getAttribute( 'data-post-id' );
	render( <List id={ id } />, rootElement );
}