package controllers;

import com.jfoenix.controls.JFXButton;
import com.jfoenix.controls.JFXComboBox;
import com.jfoenix.controls.JFXPasswordField;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.Parent;
import javafx.scene.control.Alert;
import javafx.scene.control.Label;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;

import java.io.File;
import java.io.IOException;
import java.net.URL;
import java.util.ResourceBundle;

import com.jfoenix.controls.JFXTextField;
import javafx.scene.input.MouseEvent;
import javafx.scene.layout.AnchorPane;
import javafx.scene.layout.Pane;
import javafx.stage.FileChooser;
import javafx.stage.Stage;
import models.Status;
import models.User;
import models.UserRole;
import services.UserService;
import util.PasswordHasher;

public class RegistrationUI implements Initializable {
    private final UserService ps = new UserService();


    private ObservableList<String> list = FXCollections.observableArrayList();
    @FXML
    private JFXTextField adressTF;
    @FXML
    private JFXButton choosefileBT;
    @FXML
    private JFXPasswordField cmdpPF;
    @FXML
    private JFXTextField emailTF;
    @FXML
    private JFXPasswordField mdpPF;
    @FXML
    private JFXTextField nomTF;
    @FXML
    private JFXTextField prenomTF;
    @FXML
    private AnchorPane pane;
    @FXML
    private JFXComboBox<String> roleCB;
    @FXML
    private JFXTextField telTF;
    @FXML
    private ImageView reduceIcon;
    @FXML
    private Pane signUpPane;
    @FXML
    private Label registrationlabel;
    @FXML
    private Label emaillabel;
    @FXML
    private Label mdplabel;
    @FXML
    private Label phonelabel;
    @FXML
    private ImageView userPhoto;

    public Pane getParentPane() {
        return signUpPane;
    }
    private UserService us = new UserService();


    @FXML
    void chooseFile(ActionEvent event) {
        FileChooser fileChooser = new FileChooser();
        fileChooser.setTitle("Choose Photo");
        fileChooser.getExtensionFilters().addAll(
                new FileChooser.ExtensionFilter("Image Files", "*.png", "*.jpg", "*.gif")
        );
        File selectedFile = fileChooser.showOpenDialog(userPhoto.getScene().getWindow());
        if (selectedFile != null) {
            Image image = new Image(selectedFile.toURI().toString());
            userPhoto.setImage(image);
        }
    }

    private boolean validateFields() {
        String telPattern = "\\d{8}";
        String emailPattern = "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$";
        String password = mdpPF.getText();
        String confirmPassword = cmdpPF.getText();

        if (!nomTF.getText().isEmpty() && !prenomTF.getText().isEmpty() && !emailTF.getText().isEmpty() && roleCB.getValue() != null &&
                !telTF.getText().isEmpty() && !adressTF.getText().isEmpty() && !mdpPF.getText().isEmpty() && !cmdpPF.getText().isEmpty()) {

            if (!emailTF.getText().matches(emailPattern)) {
                registrationlabel.setText("Please enter a valid email address.");
                return false;
            }
            else if (us.doesEmailExist(emailTF.getText()))
            {
                registrationlabel.setText("This email already exists return to Sign in page.");
                return false;
            }
          else  if (!telTF.getText().matches(telPattern)) {
                registrationlabel.setText("Please enter a valid phone number (8 digits).");
                return false;
            }

            else if (password.length() < 8 || !password.matches("^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d).{8,}$")) {
                registrationlabel.setText("Password must be at least 8 characters long \n and contain one uppercase letter, one lowercase letter");
                return false;
            }

            else if (!password.equals(confirmPassword)) {
                registrationlabel.setText("Password and Confirm Password do not match.");
                return false;
            }
            registrationlabel.setText("");
            return true;
        } else {
            registrationlabel.setText("Please fill all necessary fields ");
            return false;
        }
    }

    private void addUserToDatabase() {
        try {
            String photo = userPhoto.getImage() != null ? userPhoto.getImage().getUrl() : "";
            String nom = nomTF.getText();
            String prenom = prenomTF.getText();
            String email = emailTF.getText();
            int tel = Integer.parseInt(telTF.getText());
            String address = adressTF.getText();
            Status status = Status.ACTIVE;
            String hashedPassword = PasswordHasher.hashPassword(mdpPF.getText());
            String role = roleCB.getValue(); // Get role exactly as selected

            User user = new User(tel, nom, prenom, email, hashedPassword, role, address, status, photo);
            ps.ajouter(user);
            clearFields();
            registrationlabel.setText("Successfully registered");
            Alert alert = new Alert(Alert.AlertType.CONFIRMATION);
            alert.setTitle("Registration Successful");
            alert.setHeaderText(null);
            alert.setContentText("User registered successfully.");
            alert.showAndWait();
            Parent root = FXMLLoader.load(getClass().getResource("/MainUI.fxml"));
            emailTF.getScene().setRoot(root);
        } catch (Exception e) {
            registrationlabel.setText("Registration failed: " + e.getMessage());
            e.printStackTrace();
        }
    }
    @FXML
    void AddUser(ActionEvent event) throws IOException {
        if (validateFields()) {
            addUserToDatabase();
        }
    }
    @FXML
    private void close_app(MouseEvent event) {
        System.exit(0);
    }
    @FXML
    private void back_to_menu (MouseEvent event) throws IOException {
        Parent root = FXMLLoader.load(getClass().getResource("/MainUI.fxml"));
        emailTF.getScene().setRoot(root);
    }


    public void selectRole(ObservableList<String> list) {
        list.add(UserRole.PARTICIPANT);
        list.add(UserRole.JURY);
        list.add(UserRole.COACH);
        list.add(UserRole.ORGANISATEUR);
    }
    @FXML
    private void reduceWindow(MouseEvent event) {
        // Get the stage from any node in the scene
        Stage stage = (Stage) reduceIcon.getScene().getWindow();
        // Minimize the stage
        stage.setIconified(true);
    }

    private void clearFields() {
        nomTF.clear();
        prenomTF.clear();
        emailTF.clear();
        telTF.clear();
        adressTF.clear();
        mdpPF.clear();
        cmdpPF.clear();
        roleCB.getSelectionModel().clearSelection();
        choosefileBT.setText("");
    }
    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {
        selectRole(list);
        roleCB.setItems(list);

        // Only validate phone number format while typing
        telTF.textProperty().addListener((__, ___, newValue) -> {
            if (!newValue.matches("\\d*")) {
                telTF.setText(newValue.replaceAll("[^\\d]", ""));
            }

            if (telTF.getText().length() > 8) {
                String limitedText = telTF.getText().substring(0, 8);
                telTF.setText(limitedText);
            }
        });
    }
}
