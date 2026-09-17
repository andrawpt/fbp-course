using Avalonia.Controls;
using Avalonia.Input;
using Avalonia.Interactivity;
using Avalonia.Media;
using System;
using System.Globalization;

namespace Exc_3
{
    public partial class MainWindow : Window
    {
        private const int MAX_DIGITS = 15;

        private string _inputSekarang = "";
        private double _angkaPertama = 0;
        private string _operasi = "";
        private bool _habisSamaDengan = false;
        private bool _isDegreeMode = true;

        public MainWindow()
        {
            InitializeComponent();

            AddHandler(TextInputEvent, OnWindowTextInput, RoutingStrategies.Tunnel | RoutingStrategies.Bubble);
        }

        private void OnModeToggleClick(object? sender, RoutedEventArgs e)
        {
            _isDegreeMode = !_isDegreeMode;
            BtnMode.Content = _isDegreeMode ? "DEG" : "RAD";
            BtnMode.Foreground = _isDegreeMode 
                ? Brush.Parse("#4fc3f7") 
                : Brush.Parse("#ffb74d");
        }

        private void OnConstantClick(object? sender, RoutedEventArgs e)
        {
            if (sender is Button button && button.Tag is string tag)
            {
                double val = tag switch
                {
                    "pi" => Math.PI,
                    "e" => Math.E,
                    _ => 0
                };
                _inputSekarang = FormatHasil(val);
                _habisSamaDengan = false;
                UpdateLayar();
            }
        }

        private void OnScientificUnaryClick(object? sender, RoutedEventArgs e)
        {
            if (sender is Button button && button.Tag is string func)
            {
                double val = string.IsNullOrEmpty(_inputSekarang)
                    ? 0
                    : double.Parse(_inputSekarang, CultureInfo.InvariantCulture);

                double result = 0;
                bool isError = false;
                string errorMsg = "Error";
                string historyText = "";

                switch (func)
                {
                    case "sin":
                        double angleSin = _isDegreeMode ? (val * Math.PI / 180.0) : val;
                        result = Math.Sin(angleSin);
                        if (_isDegreeMode && Math.Abs(val % 180) < 1e-9) result = 0;
                        historyText = $"sin({FormatHasil(val)}{(_isDegreeMode ? "°" : "")})";
                        break;

                    case "cos":
                        double angleCos = _isDegreeMode ? (val * Math.PI / 180.0) : val;
                        result = Math.Cos(angleCos);
                        if (_isDegreeMode && Math.Abs((val - 90) % 180) < 1e-9) result = 0;
                        historyText = $"cos({FormatHasil(val)}{(_isDegreeMode ? "°" : "")})";
                        break;

                    case "tan":
                        double angleTan = _isDegreeMode ? (val * Math.PI / 180.0) : val;
                        if (_isDegreeMode && Math.Abs((val - 90) % 180) < 1e-9)
                        {
                            isError = true;
                            errorMsg = "Domain Error";
                        }
                        else
                        {
                            result = Math.Tan(angleTan);
                        }
                        historyText = $"tan({FormatHasil(val)}{(_isDegreeMode ? "°" : "")})";
                        break;

                    case "sqr":
                        result = val * val;
                        historyText = $"sqr({FormatHasil(val)})";
                        break;

                    case "sqrt":
                        if (val < 0)
                        {
                            isError = true;
                            errorMsg = "Invalid Input";
                        }
                        else
                        {
                            result = Math.Sqrt(val);
                        }
                        historyText = $"√({FormatHasil(val)})";
                        break;

                    case "ln":
                        if (val <= 0)
                        {
                            isError = true;
                            errorMsg = "Invalid Input";
                        }
                        else
                        {
                            result = Math.Log(val);
                        }
                        historyText = $"ln({FormatHasil(val)})";
                        break;

                    case "log":
                        if (val <= 0)
                        {
                            isError = true;
                            errorMsg = "Invalid Input";
                        }
                        else
                        {
                            result = Math.Log10(val);
                        }
                        historyText = $"log({FormatHasil(val)})";
                        break;

                    case "recip":
                        if (val == 0)
                        {
                            isError = true;
                            errorMsg = "Divide by 0";
                        }
                        else
                        {
                            result = 1 / val;
                        }
                        historyText = $"1/({FormatHasil(val)})";
                        break;

                    case "fact":
                        if (val < 0 || val != Math.Floor(val) || val > 170)
                        {
                            isError = true;
                            errorMsg = "Invalid Input";
                        }
                        else
                        {
                            result = HitungFaktorial((int)val);
                        }
                        historyText = $"{FormatHasil(val)}!";
                        break;
                }

                if (isError)
                {
                    TextLayar.Text = errorMsg;
                    _inputSekarang = "";
                    _habisSamaDengan = true;
                }
                else
                {
                    TextRiwayat.Text = historyText;
                    _inputSekarang = FormatHasil(result);
                    UpdateLayar();
                    _habisSamaDengan = true;
                }
            }
        }

        private double HitungFaktorial(int n)
        {
            double res = 1;
            for (int i = 2; i <= n; i++)
            {
                res *= i;
            }
            return res;
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
            else if (c == '^')
            {
                InputOperator("^");
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

            // Hitung jumlah digit angka (selain titik desimal dan tanda negatif)
            int digitCount = 0;
            foreach (char c in _inputSekarang)
            {
                if (char.IsDigit(c)) digitCount++;
            }

            // Batasi input manual maksimal 15 digit angka
            if (digit != "." && digitCount >= MAX_DIGITS)
                return;

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
                _inputSekarang = FormatHasil(angkaSaatIni / 100);
                UpdateLayar();
                return;
            }

            _angkaPertama = angkaSaatIni;
            _operasi = opSimbol;

            string displayOp = opSimbol switch
            {
                "*" => "×",
                "/" => "÷",
                "^" => "^",
                _ => opSimbol
            };

            TextRiwayat.Text = $"{FormatHasil(_angkaPertama)} {displayOp}";
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
                case "^":
                    hasil = Math.Pow(_angkaPertama, angkaKedua);
                    break;
            }

            if (!adaError)
            {
                string displayOp = _operasi switch
                {
                    "*" => "×",
                    "/" => "÷",
                    "^" => "^",
                    _ => _operasi
                };
                TextRiwayat.Text = $"{FormatHasil(_angkaPertama)} {displayOp} {FormatHasil(angkaKedua)} =";
                string formattedHasil = FormatHasil(hasil);
                TextLayar.Text = formattedHasil;
                _inputSekarang = formattedHasil;
            }

            _operasi = "";
            _habisSamaDengan = true;
        }

        private string FormatHasil(double val)
        {
            if (double.IsNaN(val)) return "NaN";
            if (double.IsPositiveInfinity(val) || double.IsNegativeInfinity(val)) return "Overflow";
            if (val == 0) return "0";

            double absVal = Math.Abs(val);

            // Jika nilai sangat besar (>= 1e15) atau sangat kecil (< 1e-7), gunakan notasi ilmiah
            if (absVal >= 1e15 || absVal < 1e-7)
            {
                return val.ToString("G12", CultureInfo.InvariantCulture);
            }

            // Gunakan format G15 untuk mencegah artefak presisi float
            return val.ToString("G15", CultureInfo.InvariantCulture);
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