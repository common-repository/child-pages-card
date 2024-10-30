import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import { ColorPalette, TextControl, RadioControl, RangeControl } from '@wordpress/components';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';

export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();
	return (
		<div { ...blockProps }>
			<ServerSideRender
				block = 'child-pages/childpagescard-block'
				attributes = { attributes }
			/>
			<TextControl
				label = { __( 'ID or Slug of the parent page', 'child-pages-card' ) }
				placeholder = { __( 'ID or Slug or Blank', 'child-pages-card' ) }
				help = { __( 'Normally blank. Enter only when you view a child page that takes a parent page other than this page.', 'child-pages-card' ) }
				value = { attributes.pageid }
				onChange = { ( value ) => setAttributes( { pageid: value } ) }
			/>

			<InspectorControls>
				<TextControl
					label = { __( 'ID or Slug of the parent page', 'child-pages-card' ) }
					placeholder = { __( 'ID or Slug or Blank', 'child-pages-card' ) }
					help = { __( 'Normally blank. Enter only when you view a child page that takes a parent page other than this page.', 'child-pages-card' ) }
					value = { attributes.pageid }
					onChange = { ( value ) => setAttributes( { pageid: value } ) }
				/>
				<RadioControl
					label = { __( 'Sort Order', 'child-pages-card' ) }
					help = { __( 'Sort by child page order.', 'child-pages-card' ) }
					selected = { attributes.sort }
					onChange = { ( value ) => setAttributes( { sort: value } ) }
					options = { [
						{ label: __( 'Ascending', 'child-pages-card' ), value: 'ASC' },
						{ label: __( 'Descending', 'child-pages-card' ), value: 'DESC' },
					] }
				/>
				<RangeControl
					label = { __( 'Excerpt', 'child-pages-card' ) }
					max = { 500 }
					min = { 0 }
					value = { attributes.excerpt }
					onChange = { ( value ) => setAttributes( { excerpt: value } ) }
				/>
				<RangeControl
					label = { __( 'Image sizes', 'child-pages-card' ) }
					max = { 200 }
					min = { 0 }
					value = { attributes.imgsize }
					onChange = { ( value ) => setAttributes( { imgsize: value } ) }
				/>
				<RadioControl
					label = { __( 'Image position', 'child-pages-card' ) }
					selected = { attributes.img_pos }
					onChange = { ( value ) => setAttributes( { img_pos: value } ) }
					options = { [
					{ label: __( 'Left', 'child-pages-card' ), value: 'left' },
					{ label: __( 'Right', 'child-pages-card' ), value: 'right' },
					] }
				/>
				{ __( 'Border color', 'child-pages-card' ) }
				<ColorPalette
					colors = { [
						{ name: __( 'White', 'child-pages-card' ),  color: '#ffffff' },
						{ name: __( 'Black', 'child-pages-card' ),  color: '#000000' },
						{ name: __( 'Red', 'child-pages-card' ),    color: '#ff0000' },
						{ name: __( 'Yellow', 'child-pages-card' ), color: '#ffff00' },
						{ name: __( 'Blue', 'child-pages-card' ),   color: '#0000ff' },
					] }
					value = { attributes.color }
					onChange = { ( value ) => setAttributes( { color: value } ) }
				/>
				<RangeControl
					label = { __( 'Border color width', 'child-pages-card' ) }
					max = { 15 }
					min = { 0 }
					value = { attributes.color_width }
					onChange = { ( value ) => setAttributes( { color_width: value } ) }
				/>
				<RangeControl
					label = { __( 'Title line height', 'child-pages-card' ) }
					max = { 300 }
					min = { 10 }
					value = { attributes.t_line_height }
					onChange = { ( value ) => setAttributes( { t_line_height: value } ) }
				/>
				<RangeControl
					label = { __( 'Excerpt line height', 'child-pages-card' ) }
					max = { 300 }
					min = { 10 }
					value = { attributes.d_line_height }
					onChange = { ( value ) => setAttributes( { d_line_height: value } ) }
				/>
			</InspectorControls>
		</div>
	);
}
