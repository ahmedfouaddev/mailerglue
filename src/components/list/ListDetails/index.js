import { __ } from '@wordpress/i18n';
import { render, Component, Fragment, useState, useCallback, useEffect, useRef } from '@wordpress/element';
import { useFocus } from '@helpers/use-focus';

import {
	__experimentalSpacer as Spacer,
	__experimentalHStack as HStack,
	__experimentalText as Text,
	Flex,
	FlexItem,
	FlexBlock,
	BaseControl,
	TextControl,
	TextareaControl,
	Button,
	Notice
} from '@wordpress/components';

import { theme } from '@data/theme';

import apiFetch from '@wordpress/api-fetch';

const ListDetails = props => {

	const { attributes, setAttributes } = props;

	const { list, api_url, api_key, welcome } = mailerglue_backend;

	const { sizes, fontweight, colors, gap, gaps } = theme;

	const [state, setState] = useState( {
		id: list.id,
		title: list.title,
		description: list.description,
		isSaving: false,
		saveError: ''
	} );

	const saveList = (event) => {

		event.preventDefault();

		setState( prevValues => { return { ...prevValues, isSaving: true, saveError: null } } );

		apiFetch( {
			path: api_url + '/save_list',
			method: 'post',
			data: state,
			headers: {
				'MAILERGLUE-API-KEY' : api_key,
			},
		} ).then(
			( response ) => {
				setState( prevValues => { return { ...prevValues, isSaving: false } } );
				setAttributes( prevValues => { return { ...prevValues, title: response.title, showSnackbar: true, snackbarMessage: 'List saved successfully.' } } );

				setTimeout( () => {
					setAttributes( prevValues => { return { ...prevValues, showSnackbar: false } } );
				}, 1500 );
			},
			( error ) => {
				if ( error.message ) {
					setState( prevValues => { return { ...prevValues, isSaving: false, saveError: error.message } } );
				}
			}
		);

	};

	return (
		<FlexBlock style={ { backgroundColor: colors.bg.gray1, padding: "32px", border: '1px solid #ededed' } }>

			<Flex gap={ gaps.m } direction="column" style={ { width: '100%' } }>

				<FlexBlock>
					<h3>List details</h3>
				</FlexBlock>

				<FlexBlock>
					<TextControl
						label="List name"
						value={ state.title }
						__nextHasNoMarginBottom={ true }
						onChange={ (value) => { setState( prevValues => { return { ...prevValues, title: value } } ) } }
					/>
				</FlexBlock>

				<FlexBlock>
					<TextareaControl
						label="Description"
						help="Subscribers will not see this. For your reference only."
						value={ state.description }
						__nextHasNoMarginBottom={ true }
						onChange={ ( value ) => {
							setState( prevValues => {
								return { ...prevValues, description: value }
							} );
						} }
					/>
				</FlexBlock>

				<FlexBlock>
					<Button
						variant="primary"
						onClick={ saveList }
						disabled={ state.isSaving }
						isBusy={ state.isSaving }
					>
						{ state.isSaving && <>Saving...</> }
						{ ! state.isSaving && <>Save</> }
					</Button>
				</FlexBlock>

				{ state.saveError &&
				<FlexBlock>
					<Notice
						isDismissible={false}
						status="error"
					>{ state.saveError }</Notice>
				</FlexBlock>
				}

			</Flex>

		</FlexBlock>
	);

}

export default ListDetails;