import './childpagescardadmin.css';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import {
	ColorPalette,
	RadioControl,
	RangeControl,
	SelectControl,
	TextControl,
	ToggleControl
} from '@wordpress/components';
import {
	useState,
	useEffect
} from '@wordpress/element';
import Credit from './credit';

const ChildPagesCardAdmin = () => {

	const childpagescard_options = JSON.parse( child_pages_card_settings_script_data.options );

	const childpagescard_options_pageid = childpagescard_options.pageid;
	const [ pageId, updatepageId ] = useState( childpagescard_options_pageid );

	const childpagescard_options_sort = childpagescard_options.sort;
	const [ soRt, updatesoRt ] = useState( childpagescard_options_sort );

	const childpagescard_options_excerpt = childpagescard_options.excerpt;
	const [ exCerpt, updateexCerpt ] = useState( childpagescard_options_excerpt );

	const childpagescard_options_imgsize = childpagescard_options.imgsize;
	const [ imgSize, updateimgSize ] = useState( childpagescard_options_imgsize );

	const childpagescard_options_img_pos = childpagescard_options.img_pos;
	const [ imgPos, updateimgPos ] = useState( childpagescard_options_img_pos );

	const childpagescard_options_color = childpagescard_options.color;
	const [ coLor, updatecoLor ] = useState( childpagescard_options_color );
	
	const childpagescard_options_color_width = childpagescard_options.color_width;
	const [ colorWidth, updatecolorWidth ] = useState( childpagescard_options_color_width );

	const childpagescard_options_t_line_height= childpagescard_options.t_line_height;
	const [ tlineHeight, updatetlineHeight ] = useState( childpagescard_options_t_line_height );

	const childpagescard_options_d_line_height= childpagescard_options.d_line_height;
	const [ dlineHeight, updatedlineHeight ] = useState( childpagescard_options_d_line_height );

	const childpagescard_template = child_pages_card_settings_script_data.template;
	const [ temPlate, updatetemPlate ] = useState( childpagescard_template );

	const childpagescard_template_label_value = JSON.parse( child_pages_card_settings_script_data.template_label_value );

	const childpagescard_template_overviews = JSON.parse( child_pages_card_settings_script_data.template_overviews );

	useEffect( () => {
		apiFetch( {
			path: 'rf/childpagescard_set_api/token',
			method: 'POST',
			data: {
				pageid: pageId,
				sort: soRt,
				excerpt: exCerpt,
				imgsize: imgSize,
				img_pos: imgPos,
				color: coLor,
				color_width: colorWidth,
				t_line_height: tlineHeight,
				d_line_height: dlineHeight,
				template: temPlate,
			}
		} ).then( ( response ) => {
			//console.log( response );
		} );
	}, [ pageId, soRt, exCerpt, imgSize, imgPos, coLor, colorWidth, tlineHeight, dlineHeight, temPlate ] );

	const items_sort = [];
	if ( typeof soRt !== 'undefined' ) {
		items_sort.push(
			<RadioControl
				selected = { soRt }
				onChange = { ( value ) => updatesoRt( value ) }
				options = { [
					{ label: __( 'Ascending', 'child-pages-card' ), value: 'ASC' },
					{ label: __( 'Descending', 'child-pages-card' ), value: 'DESC' },
				] }
			/>
		);
	}
	//console.log( soRt );

	const items_excerpt = [];
	if ( typeof exCerpt !== 'undefined' ) {
		items_excerpt.push(
			<RangeControl
				max = { 300 }
				min = { 0 }
				value = { exCerpt }
				onChange = { ( value ) => updateexCerpt( value ) }
			/>
		);
	}
	//console.log( exCerpt );

	const items_imgsize = [];
	if ( typeof imgSize !== 'undefined' ) {
		items_imgsize.push(
			<RangeControl
				max = { 300 }
				min = { 0 }
				value={ imgSize }
				onChange={ ( value ) => updateimgSize( value ) }
			/>
		);
	}
	//console.log( imgSize );

	const items_imgpos = [];
	if ( typeof imgPos !== 'undefined' ) {
		items_imgpos.push(
			<RadioControl
				selected = { imgPos }
				options = { [
					{ label: __( 'Left', 'child-pages-card' ), value: 'left' },
					{ label: __( 'Right', 'child-pages-card' ), value: 'right' },
				] }
				onChange = { ( value ) => updateimgPos( value ) }
			/>
		);
	}
	//console.log( imgPos );

	const items_color = [];
	const colors = [
		{ name: __( 'Navy', 'child-pages-card' ), color: '#000080' },
		{ name: __( 'Green', 'child-pages-card' ), color: '#008000' },
		{ name: __( 'Yellow', 'child-pages-card' ), color: '#ffff00' },
		{ name: __( 'Red', 'child-pages-card' ), color: '#ff0000' },
		{ name: __( 'Brown', 'child-pages-card' ), color: '#8f6446' },
		{ name: __( 'Black', 'child-pages-card' ), color: '#000000' },
		{ name: __( 'White', 'child-pages-card' ), color: '#ffffff' },
	];
	if ( typeof coLor !== 'undefined' ) {
		items_color.push(
			<ColorPalette
				clearable = { false }
				colors = { colors }
				value = { coLor }
				onChange = { ( value ) => updatecoLor( value ) }
			/>
		);
	}
	//console.log( coLor );

	const items_colorwidth = [];
	if ( typeof colorWidth !== 'undefined' ) {
		items_colorwidth.push(
			<RangeControl
				max = { 15 }
				min = { 0 }
				value={ colorWidth }
				onChange={ ( value ) => updatecolorWidth( value ) }
			/>
		);
	}
	//console.log( colorWidth );

	const items_t_line_height = [];
	if ( typeof tlineHeight !== 'undefined' ) {
		items_t_line_height.push(
			<RangeControl
				max = { 300 }
				min = { 10 }
				value={ tlineHeight }
				onChange={ ( value ) => updatetlineHeight( value ) }
			/>
		);
	}
	//console.log( tlineHeight );

	const items_d_line_height = [];
	if ( typeof dlineHeight !== 'undefined' ) {
		items_d_line_height.push(
			<RangeControl
				max = { 300 }
				min = { 10 }
				value={ dlineHeight }
				onChange={ ( value ) => updatedlineHeight( value ) }
			/>
		);
	}
	//console.log( dlineHeight );

	const items_templates = [];
	if ( typeof temPlate !== 'undefined' ) {
		items_templates.push(
			<SelectControl
				value = { temPlate }
				options={ childpagescard_template_label_value }
				onChange={  ( value ) => updatetemPlate( value ) }
				__nextHasNoMarginBottom
			/>
		);
	}
	//console.log( temPlate );

	const items_template_overview = [];
	if ( childpagescard_template_overviews.hasOwnProperty( temPlate ) ) {
		items_template_overview.push(
			<ul>
				<li>{ __( 'Description', 'child-pages-card' ) } : { childpagescard_template_overviews[ temPlate ]['description'] }</li>
				<li>{ __( 'Version', 'child-pages-card' ) } : { childpagescard_template_overviews[ temPlate ]['version'] }</li>
				<li>{ __( 'Author', 'child-pages-card' ) } : <a className="aStyle" href={ childpagescard_template_overviews[ temPlate ]['author_link'] } target="_blank" rel="noopener">{ childpagescard_template_overviews[ temPlate ]['author'] }</a></li>
			</ul>
		);
	}
	//console.log( childpagescard_template_overviews[ temPlate ] );

	return (
		<>
			<h2>Child Pages Card</h2>
			<Credit />
			<hr />
			<div><strong>{ __( 'Block', 'child-pages-card' ) }</strong></div>
			<div className="outer-paragraph">
				<li>{ __( 'You can search for blocks using the following three words.', 'child-pages-card' ) }</li>
				<div className="inner-paragraph">
					<div>
					<code>{ __( 'archives', 'child-pages-card' ) }</code>
					<code>{ __( 'child page', 'child-pages-card' ) }</code>
					<code>{ __( 'page', 'child-pages-card' ) }</code>
					</div>
					<figure>
					<img src={ child_pages_card_settings_script_data.img_block_search } />
					</figure>
				</div>
			</div>
			<hr />
			<div><strong>{ __( 'Shortcode', 'child-pages-card' ) }</strong></div>
			<div className="outer-paragraph">
				<li>
				<code>[childpagescard pageid="10087"]</code>
				</li>
			</div>
			<hr />
			<div><strong>{ __( 'Default attribute values', 'child-pages-card' ) }</strong></div>
			<table border="1" cellspacing="0" cellpadding="5" bordercolor="#000000">
			<tr>
			<th align="center"><strong>{ __( 'Attribute', 'child-pages-card' ) }</strong></th>
			<th align="center"><strong>{ __( 'Description', 'child-pages-card' ) }</strong></th>
			<th align="center" width="250px"><strong>{ __( 'Default value', 'child-pages-card' ) }</strong></th>
			</tr>
			<tr>
			<td align="center"><code>pageid</code></td>
			<td align="right"><strong>{ __( 'ID or Slug of the parent page', 'child-pages-card' ) }</strong></td>
			<td><div className="description">{ __( 'Normally blank. Enter only if you want to display child pages with a specific parent page.', 'child-pages-card' ) }</div></td>
			</tr>
			<tr>
			<td align="center"><code>sort</code></td>
			<td align="right"><strong>{ __( 'Sort Order', 'child-pages-card' ) }:</strong></td>
			<td>{ items_sort }</td>
			</tr>
			<tr>
			<td align="center"><code>excerpt</code></td>
			<td align="right"><strong>{ __( 'Excerpt', 'child-pages-card' ) }:</strong></td>
			<td>{ items_excerpt }</td>
			</tr>
			<tr>
			<td align="center"><code>imgsize</code></td>
			<td align="right"><strong>{ __( 'Image sizes', 'child-pages-card' ) }:</strong></td>
			<td>{ items_imgsize }</td>
			</tr>
			<tr>
			<td align="center"><code>img_pos</code></td>
			<td align="right"><strong>{ __( 'Image position', 'child-pages-card' ) }:</strong></td>
			<td>{ items_imgpos }</td>
			</tr>
			<tr>
			<td align="center"><code>color</code></td>
			<td align="right"><strong>{ __( 'Border color', 'child-pages-card' ) }:</strong></td>
			<td>{ items_color }</td>
			</tr>
			<tr>
			<td align="center"><code>color_width</code></td>
			<td align="right"><strong>{ __( 'Border color width', 'child-pages-card' ) }:</strong></td>
			<td>{ items_colorwidth }</td>
			</tr>
			<tr>
			<td align="center"><code>t_line_height</code></td>
			<td align="right"><strong>{ __( 'Title line height', 'child-pages-card' ) }:</strong></td>
			<td>{ items_t_line_height }</td>
			</tr>
			<tr>
			<td align="center"><code>d_line_height</code></td>
			<td align="right"><strong>{ __( 'Excerpt line height', 'child-pages-card' ) }:</strong></td>
			<td>{ items_d_line_height }</td>
			</tr>
			</table>
			<hr />
			<div><strong>{ __( 'Select template and CSS', 'child-pages-card' ) }</strong></div>
			<div className="outer-paragraph">
			{ items_templates }
			</div>
			<div className="outer-paragraph">
			<div><strong>{ __( 'Overview of the selected template', 'child-pages-card' ) }</strong></div>
				{ items_template_overview }
				<p className="description">{ __( 'If you create a stylish template, please contact me. If i incorporate it into this plugin, i will consider you a contributor to the plugin.', 'child-pages-card' ) }</p>
				<div>{ __( 'Template files allow for flexible customization.', 'child-pages-card' ) } -> <a className="aStyle" href="https://github.com/katsushi-kawamori/Child-Pages-Card-Templates" target="_blank" rel="noopener noreferrer">{ __( 'Customize', 'child-pages-card' ) }</a></div>
			</div>
		</>
	);

};

export default ChildPagesCardAdmin;
