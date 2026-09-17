using Avalonia.Controls;
using Avalonia.Input;
using Avalonia.Interactivity;
using System;
using System.Globalization;

namespace Exc_3
{
    public partial class MainWindow : Window
    {
        private string _inputSekarang = "";
        private double _angkaPertama = 0;
        private string _operasi = "";
        private bool _habisSamaDengan = false;

        public MainWindow()
        {
            InitializeComponent();

            AddHandler(TextInputEvent, OnWindowTextInput, RoutingStrategies.Tunnel | RoutingStrategies.Bubble);
        }

        private void OnWindowTextInput(object? sender, TextInputEventArgs e)
        {
            if (string.IsNullOrEmpty(e.Text)) return;

            char c = e.Text[0];

            if (char.IsDigit(c))
            {
                InputDigit(c.ToString());
                e.Handled = true;
            }
            else if (c == '.' || c == ',')
            {
                InputDigit(".");
                e.Handled = true;
            }
            else if (c == '+' || c == '-')
            {
                InputOperator(c.ToString());
                e.Handled = true;
            }
            else if (c == '*' || c == 'x' || c == 'X')
            {
                InputOperator("*");
                e.Handled = true;
            }
            else if (c == '/' || c == ':')
            {
                InputOperator("/");
                e.Handled = true;
            }
            else if (c == '%')
            {
                InputOperator("%");
                e.Handled = true;
            }
            else if (c == '=')
            {
                HitungHasil();
                e.Handled = true;
            }
        }

        private void OnAngkaClick(object? sender, RoutedEventArgs e)
        {
            if (sender is Button button && button.Tag is string digit)
            {
                InputDigit(digit);
            }
        }

        private void OnOperatorClick(object? sender, RoutedEventArgs e)
        {
            if (sender is Button button)
            {
                string? op = button.Tag as string;
                string? content = button.Content?.ToString();
                string opSimbol = op ?? content ?? "";
                InputOperator(opSimbol);
            }
        }

        private void OnSamaDenganClick(object? sender, RoutedEventArgs e) => HitungHasil();
        private void OnClearClick(object? sender, RoutedEventArgs e) => ClearSemua();
        private void OnBackspaceClick(object? sender, RoutedEventArgs e) => HapusKarakter();

        private void OnWindowKeyDown(object? sender, KeyEventArgs e)
        {
            if (e.Key == Key.Enter || e.Key == Key.Return)
            {
                HitungHasil();
                e.Handled = true;
            }
            else if (e.Key == Key.Multiply)
            {
                InputOperator("*");
                e.Handled = true;
            }
            else if (e.Key == Key.Divide)
            {
                InputOperator("/");
                e.Handled = true;
            }
            else if (e.Key == Key.Add)
            {
                InputOperator("+");
                e.Handled = true;
            }
            else if (e.Key == Key.Subtract)
            {
                InputOperator("-");
                e.Handled = true;
            }
            else if (e.Key == Key.Back)
            {
                HapusKarakter();
                e.Handled = true;
            }
            else if (e.Key == Key.Escape)
            {
                ClearSemua();
                e.Handled = true;
            }
        }
        
        private void InputDigit(string digit)
        {
            if (_habisSamaDengan)
            {
                _inputSekarang = "";
                _habisSamaDengan = false;
            }

            if (digit == "." && _inputSekarang.Contains('.'))
                return;

            if (_inputSekarang == "0" && digit != ".")
            {
                _inputSekarang = digit;
            }
            else
            {
                _inputSekarang += digit;
            }

            UpdateLayar();
        }

        private void InputOperator(string opSimbol)
        {
            if (string.IsNullOrEmpty(opSimbol)) return;

            double angkaSaatIni = string.IsNullOrEmpty(_inputSekarang) 
                ? 0 
                : double.Parse(_inputSekarang, CultureInfo.InvariantCulture);

            if (opSimbol == "%")
            {
                _inputSekarang = (angkaSaatIni / 100).ToString(CultureInfo.InvariantCulture);
                UpdateLayar();
                return;
            }

            _angkaPertama = angkaSaatIni;
            _operasi = opSimbol;

            string displayOp = opSimbol switch
            {
                "*" => "×",
                "/" => "÷",
                _ => opSimbol
            };

            TextRiwayat.Text = $"{_angkaPertama} {displayOp}";
            _inputSekarang = "";
            _habisSamaDengan = false;
        }

        private void HitungHasil()
        {
            if (string.IsNullOrEmpty(_operasi)) return;

            double angkaKedua = string.IsNullOrEmpty(_inputSekarang) 
                ? 0 
                : double.Parse(_inputSekarang, CultureInfo.InvariantCulture);

            double hasil = 0;
            bool adaError = false;

            switch (_operasi)
            {
                case "+": hasil = _angkaPertama + angkaKedua; break;
                case "-": hasil = _angkaPertama - angkaKedua; break;
                case "*": hasil = _angkaPertama * angkaKedua; break;
                case "/":
                    if (angkaKedua == 0)
                    {
                        TextLayar.Text = "Error: /0";
                        adaError = true;
                    }
                    else
                    {
                        hasil = _angkaPertama / angkaKedua;
                    }
                    break;
            }

            if (!adaError)
            {
                string displayOp = _operasi switch
                {
                    "*" => "×",
                    "/" => "÷",
                    _ => _operasi
                };
                TextRiwayat.Text = $"{_angkaPertama} {displayOp} {angkaKedua} =";
                TextLayar.Text = hasil.ToString(CultureInfo.InvariantCulture);
                _inputSekarang = hasil.ToString(CultureInfo.InvariantCulture);
            }

            _operasi = "";
            _habisSamaDengan = true;
        }

        private void ClearSemua()
        {
            _inputSekarang = "";
            _angkaPertama = 0;
            _operasi = "";
            TextRiwayat.Text = "";
            TextLayar.Text = "0";
            _habisSamaDengan = false;
        }

        private void HapusKarakter()
        {
            if (_inputSekarang.Length > 0)
            {
                _inputSekarang = _inputSekarang[..^1];
                UpdateLayar();
            }
        }

        private void UpdateLayar()
        {
            TextLayar.Text = string.IsNullOrEmpty(_inputSekarang) ? "0" : _inputSekarang;
        }
    }
}