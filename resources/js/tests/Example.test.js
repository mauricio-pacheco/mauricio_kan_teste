import React from 'react';
import { render, fireEvent, screen } from '@testing-library/react';
import '@testing-library/jest-dom/extend-expect';
import Example from '../../resources/js/components/Example';

describe('Example component', () => {
  test('renders file input and submit button', () => {
    render(<Example />);
    const fileInput = screen.getByLabelText('Carregar Arquivo');
    const submitButton = screen.getByText('Carregar Arquivo');

    expect(fileInput).toBeInTheDocument();
    expect(submitButton).toBeInTheDocument();
  });

  test('handles file upload and displays upload progress', () => {
    render(<Example />);

    const file = new File(['file contents'], 'file.csv', { type: 'text/csv' });
    const fileInput = screen.getByLabelText('Carregar Arquivo');
    fireEvent.change(fileInput, { target: { files: [file] } });

    const submitButton = screen.getByText('Carregar Arquivo');
    fireEvent.click(submitButton);

    const uploadMessage = screen.getByText('Arquivo enviado com sucesso.');
    expect(uploadMessage).toBeInTheDocument();

    const uploadProgress = screen.getByText('0%');
    expect(uploadProgress).toBeInTheDocument();
  });
});
