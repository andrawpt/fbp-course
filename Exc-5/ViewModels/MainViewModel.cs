using Exc_5.Data;
using Exc_5.Models;
using System.Collections.ObjectModel;
using System.Windows.Input;

namespace Exc_5.ViewModels
{
    public class MainViewModel : BaseViewModel
    {
        private readonly DatabaseHelper _db = new();

        public ObservableCollection<Student> Students { get; set; } = new();

        private Student? _selectedStudent;
        public Student? SelectedStudent
        {
            get => _selectedStudent;
            set
            {
                _selectedStudent = value;
                OnPropertyChanged();
                if (value != null)
                {
                    NimInput = value.Nim;
                    NameInput = value.Name;
                    MajorInput = value.Major;
                    EmailInput = value.Email;
                }
            }
        }

        private string _nimInput = string.Empty;
        public string NimInput
        {
            get => _nimInput;
            set { _nimInput = value; OnPropertyChanged(); }
        }

        private string _nameInput = string.Empty;
        public string NameInput
        {
            get => _nameInput;
            set { _nameInput = value; OnPropertyChanged(); }
        }

        private string _majorInput = string.Empty;
        public string MajorInput
        {
            get => _majorInput;
            set { _majorInput = value; OnPropertyChanged(); }
        }

        private string _emailInput = string.Empty;
        public string EmailInput
        {
            get => _emailInput;
            set { _emailInput = value; OnPropertyChanged(); }
        }

        public ICommand SaveCommand { get; }
        public ICommand UpdateCommand { get; }
        public ICommand DeleteCommand { get; }
        public ICommand ClearCommand { get; }

        public MainViewModel()
        {
            SaveCommand = new RelayCommand(_ => AddStudent());
            UpdateCommand = new RelayCommand(_ => EditStudent(), _ => SelectedStudent != null);
            DeleteCommand = new RelayCommand(_ => RemoveStudent(), _ => SelectedStudent != null);
            ClearCommand = new RelayCommand(_ => ResetForm());

            LoadData();
        }

        private void LoadData()
        {
            Students.Clear();
            var data = _db.GetAll();
            foreach (var item in data)
            {
                Students.Add(item);
            }
        }

        private void AddStudent()
        {
            if (string.IsNullOrWhiteSpace(NimInput) || string.IsNullOrWhiteSpace(NameInput)) return;

            var student = new Student
            {
                Nim = NimInput,
                Name = NameInput,
                Major = MajorInput,
                Email = EmailInput
            };

            _db.Insert(student);
            LoadData();
            ResetForm();
        }

        private void EditStudent()
        {
            if (SelectedStudent == null) return;

            SelectedStudent.Nim = NimInput;
            SelectedStudent.Name = NameInput;
            SelectedStudent.Major = MajorInput;
            SelectedStudent.Email = EmailInput;

            _db.Update(SelectedStudent);
            LoadData();
            ResetForm();
        }

        private void RemoveStudent()
        {
            if (SelectedStudent == null) return;

            _db.Delete(SelectedStudent.Id);
            LoadData();
            ResetForm();
        }

        private void ResetForm()
        {
            NimInput = string.Empty;
            NameInput = string.Empty;
            MajorInput = string.Empty;
            EmailInput = string.Empty;
            SelectedStudent = null;
        }
    }
}