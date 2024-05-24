import React from 'react';
import { render, fireEvent, screen } from '@testing-library/react';
import '@testing-library/jest-dom/extend-expect'; // Para adicionar os matchers do Jest-Dom
import Example from '../../resources/js/components/Example'; // Importe o componente a ser testado

describe('Example component', () => {
  test('renders file input and submit button', () => {
    render(<Example />); // Renderize o componente

    // Verifique se os elementos do formulário estão presentes na tela
    const fileInput = screen.getByLabelText('Carregar Arquivo');
    const submitButton = screen.getByText('Carregar Arquivo');

    expect(fileInput).toBeInTheDocument();
    expect(submitButton).toBeInTheDocument();
  });

  test('handles file upload and displays upload progress', () => {
    render(<Example />); // Renderize o componente

    // Simule o envio de um arquivo
    const file = new File(['file contents'], 'file.csv', { type: 'text/csv' });
    const fileInput = screen.getByLabelText('Carregar Arquivo');
    fireEvent.change(fileInput, { target: { files: [file] } });

    // Simule o envio do formulário
    const submitButton = screen.getByText('Carregar Arquivo');
    fireEvent.click(submitButton);

    // Verifique se a mensagem de upload está sendo exibida
    const uploadMessage = screen.getByText('Arquivo enviado com sucesso.');
    expect(uploadMessage).toBeInTheDocument();

    // Verifique se o progresso de upload está sendo exibido
    const uploadProgress = screen.getByText('0%');
    expect(uploadProgress).toBeInTheDocument();
  });
});
